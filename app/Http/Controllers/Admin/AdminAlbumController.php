<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;

class AdminAlbumController extends Controller
{
    public function index(Request $request)
    {
        $query = Album::with('artist');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('album_id', $search)
                ->orWhereHas('artist', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $albums = $query->orderBy('album_id', $request->input('sort', 'desc'))->get();

        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        $artists = Artist::orderBy('name')->get();

        return view('admin.albums.create', compact('artists'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,artist_id',
            'release_year' => 'nullable|integer|min:1900|max:2100',
            'image_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Album::create($data);

        return redirect()->route('admin.albums.index')->with('success', 'Thêm album thành công!');
    }

    public function edit($id)
    {
        $album = Album::findOrFail($id);
        $artists = Artist::orderBy('name')->get();

        return view('admin.albums.edit', compact('album', 'artists'));
    }

    public function update(Request $request, $id)
    {
        $album = Album::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,artist_id',
            'release_year' => 'nullable|integer|min:1900|max:2100',
            'image_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $album->update($data);

        return redirect()->route('admin.albums.index')->with('success', 'Cập nhật album thành công!');
    }

    public function destroy($id)
    {
        Album::findOrFail($id)->delete();

        return back()->with('success', 'Album đã được xóa.');
    }
}
