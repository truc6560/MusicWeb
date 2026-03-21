<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Phải có dòng này để gọi Database

class HomeController extends Controller
{
    public function index()
    {
        // 1. LẤY TIN TỨC (10 tin mới nhất)
        $news_list = DB::table('news')->orderBy('post_date', 'desc')->limit(10)->get();

        // 2. LẤY TOP THỊNH HÀNH (Chỉ lấy 3 bài để hiển thị cột 1)
        $chart_trending = DB::table('songs')
            ->join('artists', 'songs.artist_id', '=', 'artists.artist_id')
            ->leftJoin('listen_history', 'songs.song_id', '=', 'listen_history.song_id')
            ->select('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name as artist_name', DB::raw('COUNT(listen_history.song_id) as listen_count'))
            ->groupBy('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name')
            ->orderByDesc('listen_count')
            ->limit(3)
            ->get();

        // 3. LẤY TOP VIỆT NAM (Cột 2)
        $chart_vn = DB::table('songs')
            ->join('artists', 'songs.artist_id', '=', 'artists.artist_id')
            ->leftJoin('listen_history', 'songs.song_id', '=', 'listen_history.song_id')
            ->select('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name as artist_name', DB::raw('COUNT(listen_history.song_id) as listen_count'))
            ->where(function($query) {
                $query->where('artists.country', 'LIKE', '%Viet%')
                      ->orWhere('artists.country', '=', 'VN');
            })
            ->groupBy('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name')
            ->orderByDesc('listen_count')
            ->limit(3)
            ->get();

        // 4. LẤY TOP QUỐC TẾ (Cột 3)
        $chart_usuk = DB::table('songs')
            ->join('artists', 'songs.artist_id', '=', 'artists.artist_id')
            ->leftJoin('listen_history', 'songs.song_id', '=', 'listen_history.song_id')
            ->select('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name as artist_name', DB::raw('COUNT(listen_history.song_id) as listen_count'))
            ->where('artists.country', 'NOT LIKE', '%Viet%')
            ->where('artists.country', '!=', 'VN')
            ->groupBy('songs.song_id', 'songs.title', 'songs.image_url', 'artists.name')
            ->orderByDesc('listen_count')
            ->limit(3)
            ->get();

        // Ném toàn bộ data này sang View
        return view('home', compact('news_list', 'chart_trending', 'chart_vn', 'chart_usuk'));
    }
}