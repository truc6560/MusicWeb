<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use App\Models\FavoriteSong;
use App\Models\Genre;
use App\Models\ListenHistory;
use App\Models\Song;
use App\Models\User;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalVisits = ListenHistory::count();
        $totalSongs = Song::count();
        $totalAlbums = Album::count();
        $avgListenTimeSec = Song::avg('duration');
        $avgListenTimeMin = round(($avgListenTimeSec ?? 0) / 60);
        $totalFavorites = FavoriteSong::count();

        $labels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $year = date('Y', strtotime("-$i years"));
            $labels[] = $year;
            $chartData[] = (int) User::whereYear('registration_date', $year)->count();
        }

        $topSongs = Song::with('artist')
            ->orderByDesc('plays')
            ->limit(10)
            ->get()
            ->map(function (Song $song) {
                $song->artist_name = $song->artist?->name;
                return $song;
            });

        $topArtists = Artist::withCount('favoriteArtists')
            ->orderByDesc('favorite_artists_count')
            ->limit(5)
            ->get()
            ->map(function (Artist $artist) {
                $artist->total_favs = $artist->favorite_artists_count;
                return $artist;
            });

        $totalAllPlays = Song::sum('plays');

        $trendingArtist = Artist::withSum('songs', 'plays')
            ->orderByDesc('songs_sum_plays')
            ->first();

        $genres = Genre::withCount('songs')
            ->orderByDesc('songs_count')
            ->get()
            ->map(function (Genre $genre) {
                $genre->genre_name = $genre->name;
                $genre->so_bai_hat = $genre->songs_count;
                return $genre;
            });

        $todayPlays = ListenHistory::whereDate('listened_at', today())->count();
        $monthPlays = ListenHistory::whereYear('listened_at', now()->year)
            ->whereMonth('listened_at', now()->month)
            ->count();

        $last7Days = ListenHistory::query()
            ->selectRaw('DATE(listened_at) as date, COUNT(*) as total')
            ->where('listened_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(listened_at)')
            ->orderByDesc('date')
            ->limit(7)
            ->get();

        return view('admin.statistics.index', compact(
            'totalUsers',
            'totalVisits',
            'totalSongs',
            'totalAlbums',
            'avgListenTimeMin',
            'totalFavorites',
            'labels',
            'chartData',
            'topSongs',
            'topArtists',
            'totalAllPlays',
            'trendingArtist',
            'genres',
            'todayPlays',
            'monthPlays',
            'last7Days'
        ));
    }

    public function getRealtimeStats()
    {
        $todayPlays = ListenHistory::whereDate('listened_at', today())->count();
        $topSong = Song::orderByDesc('plays')->first();

        return response()->json([
            'today_plays' => $todayPlays,
            'top_song' => $topSong ? $topSong->title : 'N/A',
            'top_song_plays' => $topSong ? $topSong->plays : 0,
        ]);
    }
}
