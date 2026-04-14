<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LibraryController extends Controller
{
    public function songs()
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để xem bài hát yêu thích.');
        }

        $likedSongs = Auth::user()
            ->likedSongs()
            ->with('artist')
            ->orderByDesc('songs.song_id')
            ->get();

        return view('client.library.index', [
            'section' => 'songs',
            'title' => 'Bài hát yêu thích',
            'subtitle' => 'Danh sách bài hát bạn đã thả tim.',
            'likedSongs' => $likedSongs,
            'likedArtists' => collect(),
            'historyItems' => collect(),
        ]);
    }

    public function artists()
    {
        if (!Auth::check()) {
            abort(403, 'Vui lòng đăng nhập để xem nghệ sĩ yêu thích.');
        }

        $likedArtists = Auth::user()
            ->likedArtists()
            ->orderByDesc('artists.artist_id')
            ->get();

        return view('client.library.index', [
            'section' => 'artists',
            'title' => 'Nghệ sĩ yêu thích',
            'subtitle' => 'Danh sách nghệ sĩ bạn đã thả tim.',
            'likedSongs' => collect(),
            'likedArtists' => $likedArtists,
            'historyItems' => collect(),
        ]);
    }

    public function history()
    {
        $isGuestHistory = !Auth::check();

        $historyItems = collect();

        if (!$isGuestHistory && Schema::hasTable('listen_history')) {
            $historyItems = DB::table('listen_history')
                ->join('songs', 'listen_history.song_id', '=', 'songs.song_id')
                ->leftJoin('artists', 'songs.artist_id', '=', 'artists.artist_id')
                ->where('listen_history.user_id', Auth::id())
                ->orderByDesc('listen_history.listened_at')
                ->select([
                    'songs.song_id',
                    'songs.title',
                    'songs.image_url',
                    'songs.audio_file',
                    'artists.name as artist_name',
                    'listen_history.listened_at',
                ])
                ->get();
        }

        return view('client.library.index', [
            'section' => 'history',
            'title' => 'Lịch sử nghe',
            'subtitle' => 'Các bài hát bạn đã nghe gần đây.',
            'likedSongs' => collect(),
            'likedArtists' => collect(),
            'historyItems' => $historyItems,
            'isGuestHistory' => $isGuestHistory,
        ]);
    }
}
