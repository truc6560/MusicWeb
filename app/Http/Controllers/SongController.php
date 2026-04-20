<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;

class SongController extends Controller
{
    protected function buildLyricFileCandidates(string $title, string $artist): array
    {
        $normalize = static function (string $value): string {
            $value = preg_replace('/[\\\\\/:*?"<>|]/u', ' ', $value) ?? $value;
            $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
            return trim($value);
        };

        $candidates = [
            $normalize($title),
            $normalize($artist . ' - ' . $title),
            $normalize($title . ' - ' . $artist),
        ];

        $filtered = array_values(array_filter($candidates, static fn ($item) => $item !== ''));
        return array_values(array_unique($filtered));
    }

    protected function resolveLyricPath(string $title, string $artist): ?string
    {
        $lyricsDir = public_path('lyrics');
        if (!is_dir($lyricsDir)) {
            return null;
        }

        foreach ($this->buildLyricFileCandidates($title, $artist) as $stem) {
            $path = $lyricsDir . DIRECTORY_SEPARATOR . $stem . '.lrc';
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    protected function normalizeAudioName(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = str_replace(['\\', '/'], ' ', $value);

        if (class_exists('Normalizer')) {
            $normalized = \Normalizer::normalize($value, \Normalizer::FORM_D);
            if (is_string($normalized) && $normalized !== '') {
                $value = preg_replace('/\p{Mn}+/u', '', $normalized) ?? $normalized;
            }
        }

        $value = str_replace(['đ', 'Đ'], 'd', $value);

        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($ascii !== false && $ascii !== '') {
            $value = mb_strtolower($ascii, 'UTF-8');
        }

        return preg_replace('/[^a-z0-9]+/i', '', $value) ?? '';
    }

    protected function resolveAudioPath(string $audioDir, string $audioFile): ?string
    {
        $relative = ltrim($audioFile, '/\\');
        $directPath = $audioDir . DIRECTORY_SEPARATOR . $relative;
        if (is_file($directPath)) {
            return $directPath;
        }

        $targetName = basename($relative);
        $targetKey = $this->normalizeAudioName($targetName);
        if ($targetKey === '') {
            return null;
        }

        foreach (glob($audioDir . DIRECTORY_SEPARATOR . '*') as $candidatePath) {
            if (!is_file($candidatePath)) {
                continue;
            }

            $candidateName = basename($candidatePath);
            if ($this->normalizeAudioName($candidateName) === $targetKey) {
                return $candidatePath;
            }
        }

        return null;
    }

    public function chitietbaihat($id)
    {
        $song = Song::with('artist')->findOrFail($id);
        $artistName = $song->artist->name ?? 'Unknown Artist';
        $hasLrc = !empty($song->lrc_file)
            || $this->resolveLyricPath((string) $song->title, (string) $artistName) !== null;

        return view('song.chitiet', compact('song', 'hasLrc'));
    }

    public function thongtinbaihat($id)
    {
        // Lấy bài hát kèm thông tin nghệ sĩ
        $song = Song::with(['artist', 'album'])->findOrFail($id);

        $audioFile = trim((string) $song->audio_file);

        // Dùng endpoint stream có hỗ trợ Range để tua trên progress bar hoạt động ổn định.
        if (preg_match('/^https?:\/\//i', $audioFile)) {
            $audioUrl = $audioFile;
        } else {
            $audioUrl = route('song.stream', ['id' => $song->song_id]);
        }
        
        $relatedSongs = Song::with('artist')
            ->where('artist_id', $song->artist_id)
            ->where('song_id', '!=', $song->song_id)
            ->orderByDesc('plays')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'song_id' => $item->song_id,
                    'title' => $item->title,
                    'artist' => optional($item->artist)->name ?? 'Unknown Artist',
                    'cover' => asset($item->image_url ?? 'image/icon2.png'),
                ];
            })
            ->values();

        $artistLiked = false;
        if (auth()->check() && $song->artist_id) {
            $artistLiked = auth()->user()
                ->likedArtists()
                ->where('artists.artist_id', $song->artist_id)
                ->exists();
        }

        $artistName = $song->artist->name ?? 'Unknown Artist';
        $fallbackLyricPath = $this->resolveLyricPath((string) $song->title, (string) $artistName);
        $fallbackLrcUrl = $fallbackLyricPath ? asset('lyrics/' . basename($fallbackLyricPath)) : null;

        return response()->json([
            'song_id' => $song->song_id,
            'title' => $song->title,
            'artist' => $artistName,
            'artist_id' => $song->artist_id,
            'album_id' => $song->album_id,
            'artist_liked' => $artistLiked,
            'lyrics' => $song->lyrics ?? 'Chưa có lời bài hát',
            'cover' => asset($song->image_url ?? 'image/icon2.png'),
            'audio_url' => $audioUrl,
            'lrc_url' => !empty($song->lrc_file) ? asset('lyrics/' . $song->lrc_file) : $fallbackLrcUrl,
            'duration' => $song->duration ?? 0,
            'album' => optional($song->album)->title ?? 'Single',
            'genres' => $song->genres ?? 'Chưa cập nhật',
            'plays' => (int) ($song->plays ?? 0),
            'related_songs' => $relatedSongs,
        ]);
    }

    public function laylyrics($id)
    {
        $song = Song::find($id);
        if (!$song) {
            return response()->json(['status' => 'error', 'message' => 'Not found']);
        }
        return response()->json([
            'status' => 'success',
            'lyrics' => $song->lyrics ?? 'Chưa có lời bài hát'
        ]);
    }

    public function stream($id, Request $request)
    {
        $song = Song::findOrFail($id);
        $audioFile = (string) $song->audio_file;
        $audioDir = public_path('audio');
        $path = $this->resolveAudioPath($audioDir, $audioFile);

        if (!is_file($path)) {
            abort(404);
        }

        $size = filesize($path);
        $start = 0;
        $end = $size - 1;
        $status = 200;

        $range = $request->header('Range');
        if ($range && preg_match('/bytes=(\d*)-(\d*)/i', $range, $matches)) {
            if ($matches[1] !== '') {
                $start = (int) $matches[1];
            }
            if ($matches[2] !== '') {
                $end = (int) $matches[2];
            }
            if ($end >= $size) {
                $end = $size - 1;
            }
            if ($start > $end || $start >= $size) {
                return response('', 416, [
                    'Content-Range' => 'bytes */' . $size,
                    'Accept-Ranges' => 'bytes',
                ]);
            }
            $status = 206;
        }

        $length = $end - $start + 1;
        $mime = mime_content_type($path) ?: 'audio/mpeg';

        $headers = [
            'Content-Type' => $mime,
            'Content-Length' => (string) $length,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
        ];

        if ($status === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(function () use ($path, $start, $length) {
            $handle = fopen($path, 'rb');
            if ($handle === false) {
                return;
            }

            fseek($handle, $start);
            $remaining = $length;
            $chunk = 8192;

            while (!feof($handle) && $remaining > 0) {
                $read = ($remaining > $chunk) ? $chunk : $remaining;
                $buffer = fread($handle, $read);
                if ($buffer === false) {
                    break;
                }
                echo $buffer;
                $remaining -= strlen($buffer);
                if (function_exists('ob_flush')) {
                    @ob_flush();
                }
                flush();
            }

            fclose($handle);
        }, $status, $headers);
    }
}
