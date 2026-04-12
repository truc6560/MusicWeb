<x-admin-layout title="Thống kê hệ thống">
    <style>
    :root { --primary: #00d1ff; --secondary: #bd00ff; --bg-dark: #0d0f17; --card-bg: #1a1c26; --border: #2d2f3b; }
    
    main {
        width: 100%;
        max-width: 100%;
        padding: 30px;
    }
    
    /* Tab Navigation */
    .tab-header {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 15px;
        flex-wrap: wrap;
    }
    
    .tab-btn {
        padding: 12px 24px;
        background: transparent;
        border: none;
        color: #a0a0a0;
        cursor: pointer;
        border-radius: 8px;
        font-size: 1rem;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }
    
    .tab-btn.active {
        background: rgba(0, 209, 255, 0.1);
        color: var(--primary);
        font-weight: bold;
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.5s;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .kpi-card {
        background: var(--card-bg);
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .kpi-card i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    .kpi-card h3 {
        font-size: 1.8rem;
        margin-bottom: 5px;
    }
    
    .kpi-card p {
        color: #888;
        font-size: 0.85rem;
    }
    
    /* Charts Layout */
    .chart-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--border);
    }
    
    .card h3 {
        margin-bottom: 20px;
        font-size: 1.2rem;
    }
    
    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th {
        text-align: left;
        color: #888;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    td {
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
    }
    
    tr:last-child td {
        border-bottom: none;
    }
    
    /* Progress Bar */
    .progress-bar {
        height: 6px;
        background: #2d2f3b;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 5px;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 10px;
    }
    
    /* Badges */
    .badge {
        background: rgba(189, 0, 255, 0.2);
        color: var(--secondary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .badge-primary {
        background: rgba(0, 209, 255, 0.1);
        color: var(--primary);
    }
    
    /* Artist Item */
    .artist-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        background: rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 10px;
    }
    
    .artist-item img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
        
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Tab Navigation -->
<div class="tab-header">
    <button class="tab-btn active" onclick="switchTab('overview')">
        <i class="fas fa-chart-pie"></i> Tổng quan tương tác
    </button>
    <button class="tab-btn" onclick="switchTab('songs')">
        <i class="fas fa-music"></i> Top Bài hát & Nghệ sĩ
    </button>
    <button class="tab-btn" onclick="switchTab('genres')">
        <i class="fas fa-tags"></i> Thống kê Thể loại
    </button>
    <button class="tab-btn" onclick="switchTab('time')">
        <i class="fas fa-clock"></i> Phân tích thời gian
    </button>
</div>

<!-- Tab 1: Tổng quan -->
<div id="overview" class="tab-content active">
    <h1 style="margin-bottom: 30px;">Dashboard Thống kê Tương tác</h1>
    
    <div class="kpi-grid">
        <div class="kpi-card">
            <i class="fas fa-users"></i>
            <div>
                <h3>{{ number_format($totalUsers) }}</h3>
                <p>Người dùng</p>
            </div>
        </div>
        <div class="kpi-card" style="border-color: var(--secondary);">
            <i class="fas fa-headphones" style="color: var(--secondary);"></i>
            <div>
                <h3>{{ number_format($totalVisits) }}</h3>
                <p>Lượt nghe</p>
            </div>
        </div>
        <div class="kpi-card" style="border-color: #ffaa00;">
            <i class="fas fa-music" style="color: #ffaa00;"></i>
            <div>
                <h3>{{ number_format($totalSongs) }}</h3>
                <p>Bài hát</p>
            </div>
        </div>
        <div class="kpi-card" style="border-color: #2ecc71;">
            <i class="fas fa-heart" style="color: #2ecc71;"></i>
            <div>
                <h3>{{ number_format($totalFavorites) }}</h3>
                <p>Yêu thích</p>
            </div>
        </div>
    </div>
    
    <div class="chart-grid">
        <div class="card">
            <h3><i class="fas fa-chart-line"></i> Đăng ký mới 6 năm gần đây</h3>
            <div style="height: 350px;">
                <canvas id="regChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3><i class="fas fa-lightbulb"></i> Insight nhanh</h3>
            <div style="margin-top: 20px; line-height: 2.5;">
                <p><i class="fas fa-check-circle" style="color: #2ecc71;"></i> Thời lượng nghe TB: <strong>{{ $avgListenTimeMin }} phút</strong></p>
                <p><i class="fas fa-check-circle" style="color: #2ecc71;"></i> Tổng số Album: <strong>{{ number_format($totalAlbums) }}</strong></p>
                <p><i class="fas fa-star" style="color: #f1c40f;"></i> Nghệ sĩ xu hướng: <strong>{{ $trendingArtist->name ?? 'N/A' }}</strong></p>
                <p><i class="fas fa-chart-simple"></i> Tỷ lệ tương tác: <strong>{{ $totalSongs > 0 ? round(($totalFavorites / $totalSongs), 2) : 0 }}%</strong></p>
            </div>
        </div>
    </div>
</div>

<!-- Tab 2: Top Bài hát & Nghệ sĩ -->
<div id="songs" class="tab-content">
    <h1 style="margin-bottom: 30px;">Phân tích Bài hát & Nghệ sĩ</h1>
    
    <div class="chart-grid">
        <div class="card">
            <h3><i class="fas fa-trophy"></i> Top 10 Bài hát phổ biến</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bài hát</th>
                            <th>Lượt nghe</th>
                            <th>Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topSongs as $index => $song)
                            @php
                                $percentage = $totalAllPlays > 0 ? ($song->plays / $totalAllPlays) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $song->title }}</strong>
                                    <br><small style="color: #666;">{{ $song->artist_name }}</small>
                                </td>
                                <td style="color: var(--primary);">{{ number_format($song->plays) }}</td>
                                <td style="min-width: 150px;">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ min($percentage * 5, 100) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card">
            <h3><i class="fas fa-star"></i> Top Nghệ sĩ Yêu thích</h3>
            @foreach($topArtists as $artist)
            <div class="artist-item">
                <img src="{{ $artist->image_url ?? 'https://via.placeholder.com/50' }}" alt="{{ $artist->name }}">
                <div style="flex: 1;">
                    <strong>{{ $artist->name }}</strong>
                    <br><small style="color: #666;">{{ $artist->country ?? 'Chưa cập nhật' }}</small>
                </div>
                <div class="badge">
                    <i class="fas fa-heart"></i> {{ number_format($artist->total_favs) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Tab 3: Thống kê Thể loại -->
<div id="genres" class="tab-content">
    <h1 style="margin-bottom: 30px;">Thống kê theo Thể loại</h1>
    
    <div class="card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên Thể loại</th>
                        <th>Số lượng bài hát</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($genres as $index => $genre)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="color: var(--secondary); font-weight: bold;">{{ $genre->genre_name }}</td>
                        <td>
                            <span class="badge badge-primary">
                                <i class="fas fa-music"></i> {{ number_format($genre->so_bai_hat) }} bài hát
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab 4: Phân tích thời gian -->
<div id="time" class="tab-content">
    <h1 style="margin-bottom: 30px;">Phân tích Lượt nghe theo Thời gian</h1>
    
    <div class="kpi-grid">
        <div class="kpi-card" style="border-color: #2ecc71;">
            <i class="fas fa-calendar-day" style="color: #2ecc71;"></i>
            <div>
                <h3>{{ number_format($todayPlays) }}</h3>
                <p>Hôm nay</p>
            </div>
        </div>
        <div class="kpi-card" style="border-color: #ffaa00;">
            <i class="fas fa-calendar-alt" style="color: #ffaa00;"></i>
            <div>
                <h3>{{ number_format($monthPlays) }}</h3>
                <p>Tháng này</p>
            </div>
        </div>
    </div>
    
    <div class="card">
        <h3><i class="fas fa-chart-bar"></i> 7 Ngày gần nhất</h3>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Lượt nghe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($last7Days as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                        <td style="color: var(--primary);">{{ number_format($day->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    function switchTab(tabId) {
        // Ẩn tất cả tab content
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Bỏ active tất cả tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Hiển thị tab được chọn
        document.getElementById(tabId).classList.add('active');
        
        // Active button tương ứng
        event.currentTarget.classList.add('active');
    }
    
    // Khởi tạo biểu đồ
    const ctx = document.getElementById('regChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Người dùng mới',
                data: @json($chartData),
                borderColor: '#00d1ff',
                backgroundColor: 'rgba(0, 209, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#00d1ff',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1a1c26',
                    titleColor: '#fff',
                    bodyColor: '#888',
                    borderColor: '#00d1ff',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#888'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#888'
                    }
                }
            }
        }
    });
</script>
</x-admin-layout>