<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller{
   public function index()
    {
    $songs = Song::with('artist') 
        ->orderBy('plays', 'desc')
        ->limit(10)
        ->get();

    return view('charts', compact('songs'));
    }
}
