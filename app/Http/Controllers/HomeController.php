<?php

namespace App\Http\Controllers;
use App\Models\News;
use App\Services\ChartRankingService;

class HomeController extends Controller
{
    protected ChartRankingService $chartRankingService;

    public function __construct(ChartRankingService $chartRankingService)
    {
        $this->chartRankingService = $chartRankingService;
    }

    public function index()
    {
        // 1. LẤY TIN TỨC (10 tin mới nhất)
        $news_list = News::orderBy('post_date', 'desc')->limit(10)->get();

        // 2-4. LẤY DỮ LIỆU BXH ĐỒNG BỘ CÙNG TRANG CHARTS
        $chart_trending = $this->chartRankingService->topTrending(3);
        $chart_vn = $this->chartRankingService->topVietnam(3);
        $chart_usuk = $this->chartRankingService->topInternational(3);

        // Ném toàn bộ data này sang View
        return view('home', compact('news_list', 'chart_trending', 'chart_vn', 'chart_usuk'));
    }
}