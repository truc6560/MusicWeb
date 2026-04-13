<?php

namespace App\Http\Controllers;

use App\Services\ChartRankingService;

class ChartController extends Controller{
   protected ChartRankingService $chartRankingService;

   public function __construct(ChartRankingService $chartRankingService)
   {
      $this->chartRankingService = $chartRankingService;
   }

   public function index()
    {
    $chart_trending = $this->chartRankingService->topTrending(10);
    $chart_vn = $this->chartRankingService->topVietnam(10);
    $chart_usuk = $this->chartRankingService->topInternational(10);

    return view('charts', compact('chart_trending', 'chart_vn', 'chart_usuk'));
    }
}
