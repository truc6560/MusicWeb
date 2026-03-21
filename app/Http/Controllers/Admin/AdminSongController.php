<?php
//gộp từ admin_song
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;

class AdminSongController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading bảng artist để tránh N+1 query
        $query = Song::with('artist'); 

        // Tìm kiếm theo tên bài hát hoặc tên nghệ sĩ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('artist', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
        $songs = $query->orderBy('song_id', $sortOrder)->paginate(10);

        return view('admin.songs.index', compact('songs'));
    }

    public function destroy($id)
    {
        Song::findOrFail($id)->delete();
        return back()->with('success', 'Bài hát đã được xóa.');
    }
}
?>