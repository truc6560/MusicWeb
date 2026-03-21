<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class AdminArtistController extends Controller
{
    // 1. Hiển thị danh sách & Tìm kiếm
    public function index(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type', 'all');

        $query = Artist::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")->orWhere('artist_id', $search);
        }

        if ($type === 'vn') $query->where('country', 'Việt Nam');
        elseif ($type === 'inter') $query->where('country', '!=', 'Việt Nam');

        $artists = $query->orderBy('artist_id', $request->input('sort', 'desc'))->get();
        return view('admin.artists.index', compact('artists'));
    }

    // 2. Mở form Thêm mới
    public function create() {
        return view('admin.artists.create');
    }

    // 3. Xử lý Lưu dữ liệu Thêm mới
    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Artist::create($request->all());
        return redirect()->route('admin.artists.index')->with('success', 'Thêm thành công!');
    }

    // ==========================================
    // 4. MỞ FORM SỬA (ĐÂY LÀ HÀM BẠN ĐANG THIẾU)
    // ==========================================
    public function edit($id) {
        // Tìm nghệ sĩ theo ID, rồi gửi dữ liệu đó sang file edit.blade.php
        $artist = Artist::findOrFail($id); 
        return view('admin.artists.edit', compact('artist'));
    }

    // 5. Xử lý Cập nhật dữ liệu khi bấm nút trên form Sửa
    public function update(Request $request, $id) {
        $artist = Artist::findOrFail($id);
        
        // Loại bỏ artist_id ra khỏi dữ liệu cập nhật để tránh lỗi bảo mật
        $artist->update($request->except(['artist_id'])); 
        
        // Sửa xong thì chuyển hướng về trang danh sách
        return redirect()->route('admin.artists.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa nghệ sĩ
    public function destroy($id) {
        $artist = Artist::findOrFail($id);
        $artist->delete();
        return back()->with('success', 'Đã xóa nghệ sĩ!');
    }
}