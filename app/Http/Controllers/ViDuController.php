<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViDuController extends Controller
{
    function test(){
    $name = "HUB";
    return view('test', ["name1"=>$name]);
 }
    function test2(){
      return "Phan Thi Ngoc";
     }

    function btn(){
     return "Nguyen Bui Minh Tu";
    }

    function btnn(){
      return "Nguyen Thi Bich Tram";
    }
    function hihi() {
      return "Nguyen Le Kieu Duyen";
    }
    function test1() {
      return "Nguyen Ngoc Bao Truc";
    }

//7.1
      public function ds()
  {
    $TL = DB::table('genre')
        ->select('genre_name','genre_name_vn')->get();
    return view('sach', compact('TL'));
  }

//7.2 

//7.3
    function topmovies()
    {
        $movies = DB::table("movie")
                    ->select("movie_name", "release_date", "vote_average")
                    ->orderBy("vote_average", "desc")
                    ->take(10)
                    ->get();

        return view("topmovies", compact("movies"));
    }
//7.4
    public function longmovies()
    {
        $movies = DB::table('movie')
                    ->where('runtime', '>', 120)
                    ->limit(10)
                    ->get();

        return view('longmovies', compact('movies'));
    }
//7.5
     function phimCanada(){
        $phim = DB::table('movie')->where('country_name', 'Canada')->get();
        return view('canada', compact('phim'));
    }
}