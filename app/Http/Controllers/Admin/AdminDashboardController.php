<?php
//gộp admin và admin2
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Artist;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Bài hát nghe nhiều nhất
        $topSong = Song::orderBy('plays', 'desc')->first();

        // 2. Nghệ sĩ yêu thích nhất (Dựa vào bảng favorite_artists)
        // Cách viết tương đương câu lệnh LEFT JOIN của bạn
        $topArtist = Artist::withCount('favoriteUsers') // Yêu cầu Model Artist có quan hệ favoriteUsers
                           ->orderBy('favorite_users_count', 'desc')
                           ->first();

        // 3. Dữ liệu biểu đồ (Top 7 bài hát)
        $chartData = Song::orderBy('plays', 'desc')
                         ->limit(7)
                         ->get(['title', 'plays']);

        $chartLabels = $chartData->pluck('title');
        $chartValues = $chartData->pluck('plays');

        return view('admin.dashboard', compact('topSong', 'topArtist', 'chartLabels', 'chartValues'));
    }
}
?>