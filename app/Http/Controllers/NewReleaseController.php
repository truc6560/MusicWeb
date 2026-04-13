<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class NewReleaseController extends Controller
{
    public function index()
    {
        // Lấy 20 bài hát mới nhất, kèm thông tin nghệ sĩ
        $songs = Song::with('artist')
                    ->orderBy('release_date', 'desc')
                    ->take(20)
                    ->get();

        return view('new_releases', compact('songs'));
    }
}