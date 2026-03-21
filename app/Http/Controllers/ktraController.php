<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ktraController extends Controller
{
    public function form() 
    { 
        return view('ktra');
    }
    public function tinhtuoi(Request $request)
    {
    $nam_sinh = $request->input("nam_sinh");
    $nam_hien_tai = date("Y");
    $tuoi = $nam_hien_tai - $nam_sinh;
    return "tuổi của bạn là:". $tuoi;
    }
}
