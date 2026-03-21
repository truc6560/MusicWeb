<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class AdminGenreController extends Controller
{
    // Hiển thị danh sách và tìm kiếm
    public function index(Request $request)
    {
        $query = Genre::query();

        // Tìm kiếm theo tên hoặc mô tả
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Sắp xếp và phân trang
        $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
        $genres = $query->orderBy('genre_id', $sortOrder)->paginate(10);

        return view('admin.genres.index', compact('genres'));
    }

    // Xử lý xóa thể loại
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        
        return back()->with('success', 'Đã xóa thể loại nhạc thành công!');
    }
}
?>
