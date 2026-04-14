<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json(['results' => []]);
        }

        $artists = Artist::query()
            ->where('name', 'like', '%' . $query . '%')
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$query . '%'])
            ->orderBy('name')
            ->limit(5)
            ->get();

        $songs = Song::with('artist')
            ->where('title', 'like', '%' . $query . '%')
            ->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', [$query . '%'])
            ->orderBy('title')
            ->limit(5)
            ->get();

        $results = [];

        foreach ($artists as $artist) {
            $results[] = [
                'type' => 'artist',
                'title' => $artist->name,
                'subtitle' => 'Nghệ sĩ',
                'image' => $artist->image_url ?: asset('image/default_artist.png'),
                'url' => route('artists.show', $artist->artist_id),
            ];
        }

        foreach ($songs as $song) {
            $results[] = [
                'type' => 'song',
                'title' => $song->title,
                'subtitle' => $song->artist->name ?? 'Bài hát',
                'image' => $song->image_url ?: asset('image/default-cover.jpg'),
                'url' => route('song.details', ['id' => $song->song_id]),
            ];
        }

        return response()->json(['results' => $results]);
    }
}
