<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    // 1. Thả tim/Bỏ tim Bài hát
    public function toggleLikeSong(Request $request)
    {
        $user = Auth::user();
        $songId = $request->song_id;

        // Giả sử Model User có hàm likedSongs() (quan hệ n-n)
        $user->likedSongs()->toggle($songId);

        return response()->json(['status' => 'success', 'message' => 'Đã cập nhật yêu thích bài hát']);
    }

    // 2. Thả tim/Bỏ tim Nghệ sĩ
    public function toggleLikeArtist(Request $request)
    {
        $user = Auth::user();
        $artistId = $request->artist_id;

        $user->likedArtists()->toggle($artistId);

        return response()->json(['status' => 'success', 'message' => 'Đã cập nhật nghệ sĩ yêu thích']);
    }

    // 3. Lưu lịch sử nghe (Gọi sau khi nghe được X giây)
    public function recordHistory(Request $request)
    {
        $user = Auth::user();
        $songId = $request->song_id;

        // Lưu vào bảng listen_history (bạn nhớ tạo bảng này nhé)
        DB::table('listen_history')->updateOrInsert(
            ['user_id' => $user->id, 'song_id' => $songId],
            ['listened_at' => now()]
        );

        return response()->json(['status' => 'success']);
    }

    // 4. Cộng 1 lượt nghe (Gọi khi bài hát kết thúc)
    public function incrementPlayCount(Request $request)
    {
        $song = Song::find($request->song_id);
        if ($song) {
            $song->increment('plays');
            return response()->json(['status' => 'success', 'views' => $song->plays]);
        }
        return response()->json(['status' => 'error'], 404);
    }
}