<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Song;

class GenreController extends Controller
{
    public function show($id)
    {
        $genre = Genre::findOrFail($id);

        $songs = $genre->songs()
            ->with('artist')
            ->orderByDesc('plays')
            ->get();

        // Fallback cho dữ liệu cũ: một số bài chỉ lưu tên thể loại trong cột songs.genres.
        if ($songs->isEmpty()) {
            $songs = Song::with('artist')
                ->where('genres', 'like', '%' . $genre->name . '%')
                ->orderByDesc('plays')
                ->get();
        }

        return view('genres.show', compact('genre', 'songs'));
    }
}
