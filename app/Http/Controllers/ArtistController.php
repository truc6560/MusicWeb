<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    /**
     * 1. TRANG DANH SÁCH NGHỆ SĨ (INDEX)
     * Hiển thị lưới nghệ sĩ, hỗ trợ tìm kiếm và trạng thái Follow
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $userId = Auth::id();

        $artists = Artist::when($search, function($query, $search) {
                return $query->where('name', 'LIKE', "%$search%");
            })
            ->addSelect([
                // Subquery kiểm tra xem người dùng hiện tại đã Follow nghệ sĩ này chưa
                'is_followed' => DB::table('favorite_artists')
                    ->selectRaw('count(*)')
                    ->whereColumn('artist_id', 'artists.artist_id')
                    ->where('user_id', $userId)
            ])
            ->withCount('songs') // Lấy thêm số lượng bài hát để hiển thị nếu cần
            ->orderBy('name', 'asc')
            ->get();

        // Trỏ vào view artist/index.blade.php
        return view('artist.index', compact('artists'));
    }

    /**
     * 2. TRANG CHI TIẾT NGHỆ SĨ (SHOW)
     * Hiển thị thông tin nghệ sĩ và danh sách bài hát phổ biến
     */
    public function show($id)
    {
        $userId = Auth::id();

        // Tìm nghệ sĩ hoặc trả về 404
        $artist = Artist::findOrFail($id);

        // Lấy danh sách bài hát của nghệ sĩ và kiểm tra trạng thái Like từng bài
        $songs = Song::where('artist_id', $id)
            ->addSelect([
                // Subquery kiểm tra trạng thái Like bài hát (giống AlbumController)
                'is_liked' => DB::table('favorite_songs')
                    ->selectRaw('count(*)')
                    ->whereColumn('song_id', 'songs.song_id')
                    ->where('user_id', $userId)
            ])
            ->orderBy('plays', 'desc') // Sắp xếp theo lượt nghe (Bài hát phổ biến)
            ->get();

        // Trỏ vào view artist/show.blade.php
        return view('artist.show', compact('artist', 'songs'));
    }

    /**
     * 3. XỬ LÝ AJAX TOGGLE FOLLOW NGHỆ SĨ
     * Tương tự logic Toggle Like của Album nhưng dành cho Nghệ sĩ
     */
    public function toggleFollow(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để theo dõi nghệ sĩ.'
            ], 401);
        }

        $userId = Auth::id();
        $artistId = $request->input('artist_id');

        if (!$artistId) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu ID nghệ sĩ']);
        }

        $favorite = DB::table('favorite_artists')
            ->where('user_id', $userId)
            ->where('artist_id', $artistId);

        if ($favorite->exists()) {
            $favorite->delete();
            return response()->json(['status' => 'success', 'action' => 'unliked']); // unliked để khớp với JS cũ
        } else {
            DB::table('favorite_artists')->insert([
                'user_id' => $userId,
                'artist_id' => $artistId,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'action' => 'liked']); // liked để khớp với JS cũ
        }
    }
}