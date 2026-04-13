<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    // Hiển thị danh sách Album 
    public function index()
    {
        $albums = Album::with('artist')
            ->orderBy('release_year', 'desc')
            ->get();

        return view('albums.index', compact('albums'));
    }

    // Hiển thị chi tiết Album và danh sách bài hát 
    public function show($id)
    {
        // Lấy ID người dùng nếu đã đăng nhập
        $userId = Auth::id();
        // Tìm Album, nếu không có trả về 404
        $album = Album::with('artist')->findOrFail($id);
        // Lấy danh sách bài hát và kiểm tra trạng thái Like bằng subquery 
        $songs = $album->songs()
            ->addSelect([
                'is_liked' => DB::table('favorite_songs')
                    ->selectRaw('count(*)')
                    ->whereColumn('song_id', 'songs.song_id')
                    ->where('user_id', $userId)
            ])
            ->orderBy('song_id', 'asc')
            ->get();

        return view('albums.show', compact('album', 'songs'));
    }
    // Xử lý AJAX Toggle Like 
    public function toggleLike(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để thêm bài hát vào danh sách yêu thích.'
            ], 401);
        }

        $userId = Auth::id();
        $songId = $request->input('song_id');

        // Kiểm tra xem đã like chưa
        $favorite = DB::table('favorite_songs')
            ->where('user_id', $userId)
            ->where('song_id', $songId);

        if ($favorite->exists()) {
            $favorite->delete();
            return response()->json(['status' => 'success', 'action' => 'unliked']);
        } else {
            DB::table('favorite_songs')->insert([
                'user_id' => $userId,
                'song_id' => $songId,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'action' => 'liked']);
        }
    }
}