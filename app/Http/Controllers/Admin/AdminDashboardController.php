<?php
//gộp admin và admin2
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Song;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Bài hát nghe nhiều nhất
        $topSong = Song::orderByDesc('plays')->first();
        
        // 2. Nghệ sĩ được yêu thích nhất
        $topArtist = Artist::withCount('favoriteArtists')
            ->orderByDesc('favorite_artists_count')
            ->first();
        
        // 3. Tổng số người dùng
        $totalUsers = User::count();
        
        // 4. Dữ liệu biểu đồ (Top 7 bài hát)
        $chartData = Song::query()
            ->select('title', 'plays')
            ->orderBy('plays', 'desc')
            ->limit(7)
            ->get();
        
        $chartLabels = [];
        $chartValues = [];
        foreach ($chartData as $song) {
            $chartLabels[] = $this->truncateText($song->title, 15);
            $chartValues[] = $song->plays;
        }
        
        // 5. 5 bài hát mới nhất
        $recentSongs = Song::with('artist')
            ->orderByDesc('song_id')
            ->limit(5)
            ->get()
            ->map(function (Song $song) {
                $song->artist_name = $song->artist?->name;
                return $song;
            });
        
        // 6. 5 user mới nhất
        $recentUsers = User::query()
            ->select('user_id', 'username', 'status', 'is_admin')
            ->orderBy('registration_date', 'desc')
            ->limit(5)
            ->get();
        
        // 7. Tổng số bài hát
        $totalSongs = Song::count();
        
        return view('admin.dashboard', compact(
            'topSong',
            'topArtist',
            'totalUsers',
            'chartLabels',
            'chartValues',
            'recentSongs',
            'recentUsers',
            'totalSongs'
        ));
    }
    
    private function truncateText($text, $chars = 25)
    {
        if (strlen($text) <= $chars) {
            return $text;
        }
        return substr($text, 0, $chars) . '...';
    }
}
?>