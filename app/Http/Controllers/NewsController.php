<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index()
    {
        $news_list = DB::table('news')->orderBy('post_date', 'desc')->get();
        return view('news.index', compact('news_list'));
    }
}