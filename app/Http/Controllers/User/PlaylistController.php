<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    // Hiển thị trang danh sách Playlist & Bài hát yêu thích
    public function index()
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để xem playlist.');
        }

        $userId = Auth::id();

        $playlists = Playlist::with(['songs.artist'])
            ->where('user_id', $userId)
            ->orderBy('playlist_id', 'desc')
            ->get();

        $likedSongs = Auth::user()
            ->likedSongs()
            ->with('artist')
            ->orderBy('songs.song_id', 'desc')
            ->get();

        return view('client.playlist.index', compact('playlists', 'likedSongs'));
    }

    // Tạo Playlist mới
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors(['auth' => 'Vui lòng đăng nhập để tạo playlist.']);
        }

        $request->validate(['name' => 'required|string|max:255']);
        
        Playlist::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

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
}