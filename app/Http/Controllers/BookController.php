<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

use App\Models\Book;
use App\Models\Category;

class BookController extends BaseController
/*{
    function laythongtintheloai()
    {
       $the_loai_sach = DB::table("dm_the_loai")->get(); //đồng nghĩa với select * from dm_theloai
       return view("qlsach.the_loai",compact("the_loai_sach"));  
    }

      function laythongtinsach(){
    $sach = DB::table("sach")->select("tieu_de","tac_gia")
                ->where("nha_xuat_ban","Văn Học")->get();
    
     return view("qlsach.thong_tin_sach",compact("sach"));        
  }
}
*/
{
  function laythongtintheloai()
  {
    $the_loai_sach = Category::all();
    return view("qlsach.the_loai", compact("the_loai_sach"));
  }

  function laythongtinsach()
  {
    $sach = Book::where("nha_xuat_ban","Văn Học")->get();
    return view("qlsach.thong_tin_sach",compact("sach"));
  }

  public function laysachkinhdien()
  {
    $sach_kinh_dien = DB::table('sach as s')
        ->join('dm_the_loai as t', 's.the_loai', '=', 't.id')
        ->select('s.tieu_de', 's.nha_xuat_ban', 's.tac_gia', 's.gia_ban', 's.file_anh_bia')
        ->where('t.ten_the_loai', 'Tác phẩm kinh điển')->get();
    return view('qlsach.sach_kinh_dien', compact('sach_kinh_dien'));
  }
  public function them_dl()
    {
      $category = new Category;
      $category->id = 4;
      $category->ten_the_loai = "Test";
      $category->save();
    }

  public function sua_dl()
  {
    $category = Category::find(4); #Tìm thể loại có giá trị khóa chính (id) là 4
    $category->ten_the_loai = "Văn học";
    $category->save();
  }

  public function xoa_dl()
  {
    $category = Category::find(4);
    $category->delete();
  }


}