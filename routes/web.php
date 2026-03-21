<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ViDuController;

Route::get("/dien","App\Http\Controllers\ViDuController@hihi");
Route::get("/truc","App\Http\Controllers\ViDuController@test1");
Route::get("/phanthingoc","App\Http\Controllers\ViDuController@test2");
Route::get("nguyenbuiminhtu","App\Http\Controllers\ViDuController@btn");
Route::get("/nguyenthibichtram","App\Http\Controllers\ViDuController@btnn");

Route::get("/xephangtop10","App\Http\Controllers\ViDuController@topmovies");
Route::get("/bophimCanada","App\Http\Controllers\ViDuController@phimCanada");
Route::get("/phim","App\Http\Controllers\ViDuController@topmovies");
Route::get("dsgenre","App\Http\Controllers\BookController@ds");


