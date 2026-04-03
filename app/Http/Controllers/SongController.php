<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;

class SongController extends Controller
{
    public function getSongDetails($id)
    {
        // Lấy bài hát kèm thông tin nghệ sĩ
        $song = Song::with('artist')->findOrFail($id);
        
        return response()->json([
            'song_id' => $song->song_id,
            'title' => $song->title,
            'artist' => $song->artist->name ?? 'Unknown Artist',
            'lyrics' => $song->lyrics ?? 'Chưa có lời bài hát',
            'cover' => asset($song->image_url ?? 'image/default-cover.jpg'),
            'audio_url' => asset($song->file_path),
            'duration' => $song->duration ?? 0
        ]);
    }
}
