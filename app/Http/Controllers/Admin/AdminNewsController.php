<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    // Hiển thị danh sách và tìm kiếm
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'desc');

        $news = News::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->orderBy('news_id', $sort)
            ->get();

        return view('admin.news.index', compact('news'));
    }

    public function create() {
        return view('admin.news.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        News::create([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'content' => $request->content,
            'post_date' => now()
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Thêm tin tức thành công!');
    }

    public function edit($id) {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id) {
        $news = News::findOrFail($id);
        $news->update([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'content' => $request->content,
        ]);
        return redirect()->route('admin.news.index');
    }

    public function destroy($id) {
        News::findOrFail($id)->delete();
        return redirect()->route('admin.news.index')->with('success', 'Đã xóa tin tức.');
    }
}