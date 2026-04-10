<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;

class SongController extends Controller
{
    public function chitietbaihat($id)
    {
        // Lấy bài hát kèm thông tin nghệ sĩ
        $song = Song::with('artist')->findOrFail($id);

        $audioFile = trim((string) $song->audio_file);

        // Dùng endpoint stream có hỗ trợ Range để tua trên progress bar hoạt động ổn định.
        if (preg_match('/^https?:\/\//i', $audioFile)) {
            $audioUrl = $audioFile;
        } else {
            $audioUrl = route('song.stream', ['id' => $song->song_id]);
        }
        
        return response()->json([
            'song_id' => $song->song_id,
            'title' => $song->title,
            'artist' => $song->artist->name ?? 'Unknown Artist',
            'lyrics' => $song->lyrics ?? 'Chưa có lời bài hát',
            'cover' => asset($song->image_url ?? 'image/icon2.png'),
            'audio_url' => $audioUrl,
            'duration' => $song->duration ?? 0
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
        $audioFile = ltrim((string) $song->audio_file, '/\\');

        // Chỉ cho stream file trong public/audio
        $path = public_path('audio' . DIRECTORY_SEPARATOR . $audioFile);
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
