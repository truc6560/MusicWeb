<?php
//gộp từ admin_song
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminSongController extends Controller
{
    protected function storeLrcFile(Request $request, Song $song): ?string
    {
        if (!$request->hasFile('lrc_file')) {
            return $song->lrc_file ?? null;
        }

        $upload = $request->file('lrc_file');
        if (!$upload || !$upload->isValid()) {
            return $song->lrc_file ?? null;
        }

        $directory = public_path('lyrics');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!empty($song->lrc_file)) {
            $oldPath = public_path('lyrics/' . $song->lrc_file);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $fileName = 'song_' . $song->song_id . '.lrc';
        $upload->move($directory, $fileName);

        return $fileName;
    }

    // 1. Hiển thị danh sách & Tìm kiếm
    public function index(Request $request)
    {
        $query = Song::with(['artist', 'album']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('song_id', $search)
                ->orWhereHas('artist', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('album', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
        }

        $songs = $query->orderBy('song_id', $request->input('sort', 'desc'))->get();

        return view('admin.songs.index', compact('songs'));
    }

    // 2. Mở form Thêm mới
    public function create()
    {
        $artists = Artist::orderBy('name')->get();
        $albums = Album::orderBy('title')->get();

        return view('admin.songs.create', compact('artists', 'albums'));
    }

    // 3. Xử lý Lưu dữ liệu Thêm mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,artist_id',
            'album_id' => 'nullable|exists:albums,album_id',
            'audio_file' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'duration' => 'nullable|string|max:50',
            'plays' => 'nullable|integer|min:0',
            'genres' => 'nullable|string|max:255',
            'lyrics' => 'nullable|string',
            'lrc_file' => 'nullable|file|mimes:lrc,txt|max:2048',
        ]);

        $songData = $data;
        unset($songData['lrc_file']);

        $song = Song::create($songData);
        $song->lrc_file = $this->storeLrcFile($request, $song);
        $song->save();

        return redirect()->route('admin.songs.index')->with('success', 'Thêm bài hát thành công!');
    }

    // 4. Mở form Sửa
    public function edit($id)
    {
        $song = Song::findOrFail($id);
        $artists = Artist::orderBy('name')->get();
        $albums = Album::orderBy('title')->get();

        return view('admin.songs.edit', compact('song', 'artists', 'albums'));
    }

    // 5. Xử lý Cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $song = Song::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,artist_id',
            'album_id' => 'nullable|exists:albums,album_id',
            'audio_file' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'duration' => 'nullable|string|max:50',
            'plays' => 'nullable|integer|min:0',
            'genres' => 'nullable|string|max:255',
            'lyrics' => 'nullable|string',
            'lrc_file' => 'nullable|file|mimes:lrc,txt|max:2048',
        ]);

        $songData = $data;
        unset($songData['lrc_file']);

        $song->update($songData);
        $song->lrc_file = $this->storeLrcFile($request, $song);
        $song->save();

        return redirect()->route('admin.songs.index')->with('success', 'Cập nhật bài hát thành công!');
    }

    // 6. Xóa bài hát
    public function destroy($id)
    {
        Song::findOrFail($id)->delete();
        return back()->with('success', 'Bài hát đã được xóa.');
    }
}
?>