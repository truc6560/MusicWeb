<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller{
   public function index()
    {
    $chart_trending = Song::with('artist')
        ->orderBy('plays', 'desc')
        ->limit(10)
        ->get();

    $chart_vn = Song::with('artist')
        ->whereHas('artist', function ($query) {
            $query->where('country', 'LIKE', '%Viet%')
                  ->orWhere('country', '=', 'VN');
        })
        ->orderBy('plays', 'desc')
        ->limit(10)
        ->get();

    $chart_usuk = Song::with('artist')
        ->whereHas('artist', function ($query) {
            $query->where('country', 'NOT LIKE', '%Viet%')
                  ->where('country', '!=', 'VN');
        })
        ->orderBy('plays', 'desc')
        ->limit(10)
        ->get();

    return view('charts', compact('chart_trending', 'chart_vn', 'chart_usuk'));
    }
}
