<?php

namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Song;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. LẤY TIN TỨC (10 tin mới nhất)
        $news_list = News::orderBy('post_date', 'desc')->limit(10)->get();

        $mapTrendingSong = function (Song $song) {
            $song->artist_name = $song->artist?->name;
            $song->listen_count = $song->listen_histories_count;
            return $song;
        };

        // 2. LẤY TOP THỊNH HÀNH (Chỉ lấy 3 bài để hiển thị cột 1)
        $chart_trending = Song::with('artist')
            ->withCount('listenHistories')
            ->orderByDesc('listen_histories_count')
            ->limit(3)
            ->get()
            ->map($mapTrendingSong);

        // 3. LẤY TOP VIỆT NAM (Cột 2)
        $chart_vn = Song::with('artist')
            ->withCount('listenHistories')
            ->whereHas('artist', function ($query) {
                $query->where('country', 'like', '%Viet%')
                    ->orWhere('country', 'VN');
            })
            ->orderByDesc('listen_histories_count')
            ->limit(3)
            ->get()
            ->map($mapTrendingSong);

        // 4. LẤY TOP QUỐC TẾ (Cột 3)
        $chart_usuk = Song::with('artist')
            ->withCount('listenHistories')
            ->whereHas('artist', function ($query) {
                $query->where('country', 'not like', '%Viet%')
                    ->where('country', '!=', 'VN');
            })
            ->orderByDesc('listen_histories_count')
            ->limit(3)
            ->get()
            ->map($mapTrendingSong);

        // Ném toàn bộ data này sang View
        return view('home', compact('news_list', 'chart_trending', 'chart_vn', 'chart_usuk'));
    }
}