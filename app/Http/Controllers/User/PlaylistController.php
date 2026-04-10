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
        $user = Auth::user();
        $playlists = Playlist::where('user_id', $user->id)->get();
        $likedSongs = $user->likedSongs; 

        return view('client.playlist.index', compact('playlists', 'likedSongs'));
    }

    // Tạo Playlist mới
    public function store(Request $request)
    {
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
        $playlist = Playlist::where('id', $request->playlist_id)->where('user_id', Auth::id())->firstOrFail();
        
        // Tránh thêm trùng bài hát
        $playlist->songs()->syncWithoutDetaching([$request->song_id]);

        return response()->json(['status' => 'success', 'message' => 'Đã thêm vào Playlist']);
    }
}