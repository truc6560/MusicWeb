<?php

namespace App\Http\Controllers;
use App\Models\News;
use Illuminate\Http\Request;
use App\Services\ChartRankingService;

class HomeController extends Controller
{
    public function index(ChartRankingService $chartRankingService)
    {
        // 1. LẤY TIN TỨC (10 tin mới nhất)
        $news_list = News::orderBy('post_date', 'desc')->limit(10)->get();

        // 2. LẤY TOP THỊNH HÀNH (Chỉ lấy 3 bài để hiển thị cột 1)
        $chart_trending = $chartRankingService->topTrending(3);

        // 3. LẤY TOP VIỆT NAM (Cột 2)
        $chart_vn = $chartRankingService->topVietnam(3);

        // 4. LẤY TOP QUỐC TẾ (Cột 3)
        $chart_usuk = $chartRankingService->topInternational(3);

        // Ném toàn bộ data này sang View
        return view('home', compact('news_list', 'chart_trending', 'chart_vn', 'chart_usuk'));
    }
}