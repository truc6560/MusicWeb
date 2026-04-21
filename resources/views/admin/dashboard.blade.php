<x-admin-layout title="Bảng điều khiển">

<style>
    main {
        width: 100%;
        max-width: 100%;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 25px;
        margin-top: 25px;
    }

    .card-option {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(0, 209, 255, 0.2);
        padding: 15px 20px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.3s;
        margin-bottom: 12px;
        text-decoration: none;
    }

    .card-option:hover {
        background: rgba(0, 209, 255, 0.08);
        transform: translateX(5px);
        border-color: #00d1ff;
    }

    .option-label {
        font-size: 0.85rem;
        color: #888;
        display: block;
    }

    .option-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        display: block;
        margin-top: 4px;
    }

    .icon-box {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: rgba(0, 209, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00d1ff;
    }

    .status-active {
        color: #2ecc71;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-inactive {
        color: #f1c40f;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-banned {
        color: #e74c3c;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .view-all {
        font-size: 0.9rem;
        color: #00d1ff;
        text-decoration: none;
        transition: 0.3s;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #bd00ff;
        background: transparent;
        color: #bd00ff;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        cursor: pointer;
    }

    .btn:hover {
        background: #bd00ff;
        color: #fff;
    }

    .btn-delete {
        border-color: #ff4466;
        color: #ff4466;
    }

    .btn-delete:hover {
        background: #ff4466;
        color: #fff;
    }

    #myChart {
        width: 100% !important;
        max-height: 250px;
    }
</style>

<!-- Danh sách bài hát gần đây -->
<div class="card">
    <div class="card-header">
        <span><i class="fas fa-music"></i> Quản lý bài hát</span>
        <a href="{{ route('admin.songs.index') }}" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
    </div>
    <table>
        <thead>
            <tr>
                <th width="40%">Tên bài hát</th>
                <th width="30%">Nghệ sĩ</th>
                <th width="15%">Lượt nghe</th>
                <th width="15%" style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentSongs as $song)
                <tr>
                    <td style="font-weight: 500;">{{ $song->title }}</td>
                    <td style="color: #aaa;">{{ $song->artist_name ?? 'N/A' }}</td>
                    <td>{{ number_format($song->plays) }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.songs.edit', $song->song_id) }}" class="btn">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center; color: #666;">Chưa có bài hát nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Danh sách người dùng gần đây -->
<div class="card">
    <div class="card-header">
        <span><i class="fas fa-users"></i> Quản lý người dùng</span>
        <a href="{{ route('admin.users.index') }}" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
    </div>
    <table>
        <thead>
            <tr>
                <th width="40%">Tên người dùng</th>
                <th width="20%">Trạng thái</th>
                <th width="20%">Vai trò</th>
                <th width="20%" style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers as $user)
                @php
                    $isActive = ! $user->isLocked();
                @endphp
                <tr>
                    <td>{{ $user->username }}</td>
                    <td><span class="{{ $isActive ? 'status-active' : 'status-banned' }}">{{ $isActive ? 'Hoạt động' : 'Đã khóa' }}</span></td>
                    <td>{!! $user->is_admin ? '<span style="color:#00d1ff">Admin</span>' : 'User' !!}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-delete">
                            <i class="fas fa-user-cog"></i> Quản lý
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center; color: #666;">Chưa có người dùng nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Thống kê và biểu đồ -->
<div class="stats-grid">
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-chart-bar"></i> Biểu đồ Top Hits</span>
        </div>
        <div style="height: 250px;">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: flex-start;">
        <div class="card-header">
            <span><i class="fas fa-bolt"></i> Thông số nhanh</span>
        </div>

        <a href="{{ route('admin.songs.index') }}" class="card-option">
            <div>
                <span class="option-label">Bài hát Top 1</span>
                <span class="option-value">{{ $topSong ? \Illuminate\Support\Str::limit($topSong->title, 20) : 'N/A' }}</span>
            </div>
            <div class="icon-box"><i class="fas fa-music"></i></div>
        </a>

        <a href="{{ route('admin.artists.index') }}" class="card-option">
            <div>
                <span class="option-label">Nghệ sĩ Hot nhất</span>
                <span class="option-value">{{ $topArtist ? \Illuminate\Support\Str::limit($topArtist->name, 20) : 'N/A' }}</span>
            </div>
            <div class="icon-box"><i class="fas fa-star"></i></div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="card-option">
            <div>
                <span class="option-label">Tổng người dùng</span>
                <span class="option-value" style="color: #2ecc71;">{{ number_format($totalUsers) }}</span>
            </div>
            <div class="icon-box" style="color: #2ecc71; background: rgba(46, 204, 113, 0.1);"><i class="fas fa-users"></i></div>
        </a>

        <div class="card-option">
            <div>
                <span class="option-label">Tổng bài hát</span>
                <span class="option-value">{{ number_format($totalSongs) }}</span>
            </div>
            <div class="icon-box" style="color: #bd00ff; background: rgba(189, 0, 255, 0.1);"><i class="fas fa-database"></i></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 209, 255, 1)');
    gradient.addColorStop(1, 'rgba(189, 0, 255, 0.3)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Lượt nghe',
                data: @json($chartValues),
                backgroundColor: gradient,
                borderColor: '#00d1ff',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }, 
                    ticks: { color: '#888' } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#888', font: { size: 10 } } 
                }
            }
        }
    });
</script>
</x-admin-layout>