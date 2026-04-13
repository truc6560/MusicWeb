<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    // Hiển thị trang danh sách playlist
    public function index()
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để xem playlist.');
        }

        $userId = Auth::id();

        $playlists = Playlist::withCount('songs')
            ->where('user_id', $userId)
            ->orderBy('playlist_id', 'desc')
            ->get();

        $playlists->each(function (Playlist $playlist) {
            $playlist->cover_url = $this->resolvePlaylistCoverUrl($playlist->playlist_id);
        });

        return view('client.playlist.index', compact('playlists'));
    }

    // Hiển thị chi tiết 1 playlist
    public function show($id)
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để xem playlist.');
        }

        $playlist = Playlist::with(['songs.artist'])
            ->where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $otherPlaylists = Playlist::withCount('songs')
            ->where('user_id', Auth::id())
            ->where('playlist_id', '!=', $playlist->playlist_id)
            ->orderBy('playlist_id', 'desc')
            ->get();

        $otherPlaylists->each(function (Playlist $otherPlaylist) {
            $otherPlaylist->cover_url = $this->resolvePlaylistCoverUrl($otherPlaylist->playlist_id);
        });

        $playlist->cover_url = $this->resolvePlaylistCoverUrl($playlist->playlist_id);

        return view('client.playlist.show', compact('playlist', 'otherPlaylists'));
    }

    // Cập nhật thông tin playlist (đổi tên + ảnh)
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để cập nhật playlist.');
        }

        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('name')) {
            $playlist->name = $request->name;
            $playlist->save();
        }

        if ($request->hasFile('cover_image')) {
            $coverDir = public_path('playlist-covers');
            if (!is_dir($coverDir)) {
                mkdir($coverDir, 0777, true);
            }

            foreach (glob($coverDir . DIRECTORY_SEPARATOR . $playlist->playlist_id . '.*') as $oldFile) {
                @unlink($oldFile);
            }

            $ext = strtolower($request->file('cover_image')->getClientOriginalExtension());
            $safeExt = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? $ext : 'jpg';
            $filename = $playlist->playlist_id . '.' . $safeExt;

            $request->file('cover_image')->move($coverDir, $filename);
        }

        $coverUrl = $this->resolvePlaylistCoverUrl((int) $playlist->playlist_id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật playlist.',
                'playlist' => [
                    'playlist_id' => $playlist->playlist_id,
                    'name' => $playlist->name,
                    'cover_url' => $coverUrl,
                ],
            ]);
        }

        return redirect()->route('playlist.show', ['id' => $playlist->playlist_id])
            ->with('success', 'Đã cập nhật playlist.');
    }

    // Xóa playlist
    public function destroy(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $coverDir = public_path('playlist-covers');
        foreach (glob($coverDir . DIRECTORY_SEPARATOR . $playlist->playlist_id . '.*') as $oldFile) {
            @unlink($oldFile);
        }

        $playlist->songs()->detach();
        $playlist->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa playlist.',
                'redirect_url' => route('playlist.index'),
            ]);
        }

        return redirect()->route('playlist.index')->with('success', 'Đã xóa playlist.');
    }

    // Cập nhật trạng thái công khai/riêng tư
    public function updateVisibility(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'is_public' => 'required|boolean',
        ]);

        $playlist->is_public = (bool) $validated['is_public'];
        $playlist->save();

        $message = $playlist->is_public ? 'Đã chuyển playlist sang công khai.' : 'Đã chuyển playlist sang riêng tư.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'is_public' => (bool) $playlist->is_public,
            ]);
        }

        return redirect()->route('playlist.show', ['id' => $playlist->playlist_id])->with('success', $message);
    }

    // Sao chép toàn bộ bài hát sang playlist khác
    public function copySongsToPlaylist(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $sourcePlaylist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'target_playlist_id' => 'required|integer',
        ]);

        $targetPlaylist = Playlist::where('playlist_id', $validated['target_playlist_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ((int) $targetPlaylist->playlist_id === (int) $sourcePlaylist->playlist_id) {
            return response()->json(['status' => 'error', 'message' => 'Không thể chọn chính playlist hiện tại.'], 422);
        }

        $songIds = $sourcePlaylist->songs()->pluck('songs.song_id')->all();
        if (empty($songIds)) {
            return response()->json(['status' => 'error', 'message' => 'Playlist này chưa có bài hát để sao chép.'], 422);
        }

        $targetPlaylist->songs()->syncWithoutDetaching($songIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm toàn bộ bài hát sang playlist được chọn.',
        ]);
    }

    // AJAX: tìm kiếm bài hát để thêm vào playlist
    public function searchSongs(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $query = trim((string) $request->query('q', ''));
        if ($query === '') {
            return response()->json(['status' => 'success', 'results' => []]);
        }

        $songs = Song::with('artist')
            ->where('title', 'like', '%' . $query . '%')
            ->orWhereHas('artist', function ($artistQuery) use ($query) {
                $artistQuery->where('name', 'like', '%' . $query . '%');
            })
            ->orderBy('title')
            ->limit(15)
            ->get();

        $existingSongIds = $playlist->songs()->pluck('songs.song_id')->map(fn ($songId) => (int) $songId)->all();

        $results = $songs
            ->map(function (Song $song) use ($existingSongIds) {
                $songId = (int) $song->song_id;
                return [
                    'song_id' => $songId,
                    'title' => $song->title,
                    'artist_name' => $song->artist->name ?? 'Unknown Artist',
                    'cover' => $song->image_url ?: asset('image/default-cover.jpg'),
                    'details_url' => route('song.details', ['id' => $songId]),
                    'stream_url' => route('song.stream', ['id' => $songId]),
                    'in_playlist' => in_array($songId, $existingSongIds, true),
                ];
            })
            ->values();

        return response()->json(['status' => 'success', 'results' => $results]);
    }

    // Tạo Playlist mới
    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để tạo playlist.'], 401);
            }

            return redirect()->back()->withErrors(['auth' => 'Vui lòng đăng nhập để tạo playlist.']);
        }

        $request->validate(['name' => 'required|string|max:255']);
        
        $playlist = Playlist::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo Playlist thành công!',
                'playlist' => [
                    'playlist_id' => $playlist->playlist_id,
                    'name' => $playlist->name,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Tạo Playlist thành công!');
    }

    // Thêm bài hát vào Playlist (AJAX)
    public function addSongToPlaylist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $validated = $request->validate([
            'playlist_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);

        $playlist = Playlist::where('playlist_id', $validated['playlist_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Tránh thêm trùng bài hát
        $playlist->songs()->syncWithoutDetaching([$validated['song_id']]);

        return response()->json(['status' => 'success', 'message' => 'Đã thêm vào Playlist']);
    }

    // Xóa bài hát khỏi Playlist (AJAX)
    public function removeSongFromPlaylist(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập trước.'], 401);
        }

        $validated = $request->validate([
            'song_id' => 'required|integer',
        ]);

        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $playlist->songs()->detach([$validated['song_id']]);

        return response()->json(['status' => 'success', 'message' => 'Đã xóa bài hát khỏi Playlist']);
    }

    private function resolvePlaylistCoverUrl(int $playlistId): string
    {
        $default = asset('image/playlist.png');
        $files = glob(public_path('playlist-covers/' . $playlistId . '.*'));

        if (!$files || !isset($files[0])) {
            return $default;
        }

        return asset('playlist-covers/' . basename($files[0]));
    }
}