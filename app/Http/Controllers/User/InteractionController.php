<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InteractionController extends Controller
{
    // 1. Thả tim/Bỏ tim Bài hát
    public function toggleLikeSong(Request $request)
    {
        $user = Auth::user();
        $songId = $request->song_id;

        // toggle() trả về mảng attached/detached để biết trạng thái sau cùng.
        $result = $user->likedSongs()->toggle($songId);
        $isLiked = !empty($result['attached']);

        return response()->json([
            'status' => 'success',
            'action' => $isLiked ? 'liked' : 'unliked',
            'message' => 'Đã cập nhật yêu thích bài hát'
        ]);
    }

    // 1b. Lấy trạng thái tim hiện tại của bài hát
    public function likeSongStatus(Request $request)
    {
        $user = Auth::user();
        $songId = $request->song_id;

        if (!$songId) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu song_id'], 422);
        }

        $isLiked = $user->likedSongs()->where('songs.song_id', $songId)->exists();

        return response()->json([
            'status' => 'success',
            'liked' => $isLiked,
            'action' => $isLiked ? 'liked' : 'unliked'
        ]);
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
        $songId = (int) $request->song_id;
        $userId = Auth::id();

        if (!$userId || !$songId) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu dữ liệu lịch sử nghe.'], 422);
        }

        if (!Schema::hasTable('listen_history')) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu bảng listen_history.'], 500);
        }

        // Lưu vào bảng listen_history (bạn nhớ tạo bảng này nhé)
        DB::table('listen_history')->updateOrInsert(
            ['user_id' => $userId, 'song_id' => $songId],
            ['listened_at' => now()]
        );

        return response()->json(['status' => 'success']);
    }

    // 4. Cộng 1 lượt nghe (Gọi khi bài hát kết thúc)
    public function incrementPlayCount(Request $request)
    {
        $validated = $request->validate([
            'song_id' => 'required|integer',
        ]);

        $song = Song::find($validated['song_id']);
        if ($song) {
            $song->increment('plays');
            return response()->json(['status' => 'success', 'views' => $song->plays]);
        }
        return response()->json(['status' => 'error'], 404);
    }
}