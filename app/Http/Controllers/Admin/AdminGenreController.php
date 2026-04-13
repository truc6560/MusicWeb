<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class AdminGenreController extends Controller
{
    // 1. Hiển thị danh sách & Tìm kiếm
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Genre::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('genre_id', $search)
                ->orWhere('description', 'like', "%{$search}%");
        }

        $genres = $query->orderBy('genre_id', $request->input('sort', 'desc'))->get();
        return view('admin.genres.index', compact('genres'));
    }

    // 2. Mở form Thêm mới
    public function create()
    {
        return view('admin.genres.create');
    }

    // 3. Xử lý Lưu dữ liệu Thêm mới
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Genre::create($request->all());
        return redirect()->route('admin.genres.index')->with('success', 'Thêm thành công!');
    }

    // 4. Mở form Sửa
    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('admin.genres.edit', compact('genre'));
    }

    // 5. Xử lý Cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        // Loại bỏ genre_id để tránh ghi đè khóa chính
        $genre->update($request->except(['genre_id']));

        return redirect()->route('admin.genres.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa thể loại
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return back()->with('success', 'Đã xóa thể loại nhạc thành công!');
    }
}
?>
