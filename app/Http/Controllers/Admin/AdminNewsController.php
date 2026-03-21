<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News; // Đảm bảo bạn đã tạo Model News
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    // Hiển thị danh sách và tìm kiếm
    public function index(Request $request)
    {
        $query = News::query();

        // Tìm kiếm theo tiêu đề hoặc nội dung bài viết
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }

        // Sắp xếp (mặc định ưu tiên bài viết mới nhất dựa vào post_date)
        $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
        $news = $query->orderBy('post_date', $sortOrder)->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    // Xử lý xóa tin tức
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        
        return back()->with('success', 'Đã xóa bản tin thành công!');
    }
}