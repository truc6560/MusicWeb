@extends('layouts.client')

@section('content')
    <style>
        /* CSS DÀNH RIÊNG CHO TRANG CHỦ (Đã tinh chỉnh từ bản gốc) */
        .section-header { margin: 40px 0 20px 0; }
        .section-title { 
            font-size: 20px; font-weight: 800; color: #fff; 
            text-transform: uppercase; border-left: 4px solid #bd00ff; 
            padding-left: 12px; margin-bottom: 20px;
        }
        
        /* TIN TỨC - AUTO SCROLL */
        .news-scroll-container { 
            overflow: hidden; /* Giấu thanh cuộn đi */
            white-space: nowrap; 
            padding-bottom: 15px; 
            position: relative;
            /* Tạo hiệu ứng mờ dần ở 2 mép cực xịn */
            mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
        }
        
        .news-track {
            display: inline-flex;
            gap: 20px;
            /* Chỉnh số 30s để cho nó chạy nhanh hay chậm tùy ý Trúc */
            animation: scrollNews 30s linear infinite; 
            padding-right: 20px;
        }

        /* Khi người dùng rê chuột vào thì dừng lại cho người ta đọc */
        .news-track:hover {
            animation-play-state: paused; 
        }

        /* Hiệu ứng trôi từ phải qua trái */
        @keyframes scrollNews {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 10px)); }
        }

        .news-card { 
            width: 270px; background: #161822; border-radius: 10px; 
            flex-shrink: 0; cursor: pointer; border: 1px solid rgba(255,255,255,0.03); 
            overflow: hidden; white-space: normal; transition: 0.3s;
        }
        .news-card:hover { transform: translateY(-5px); border-color: rgba(189, 0, 255, 0.4); box-shadow: 0 10px 20px rgba(189,0,255,0.15); }
        .news-img { width: 100%; height: 150px; object-fit: cover; }
        .news-content { padding: 15px; height: 100px; display: flex; flex-direction: column; justify-content: flex-end; }
        .news-big-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; } 
        /* BẢNG XẾP HẠNG */
        .charts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .chart-box { background: #14161f; border-radius: 15px; padding: 20px; border: 1px solid rgba(255,255,255,0.03); }
        .chart-box-title { font-size: 16px; font-weight: 800; margin-bottom: 15px; display: block; color: #fff; }
        .chart-row { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 8px; transition: 0.2s; cursor: pointer; }
        .chart-row:hover { background: rgba(255,255,255,0.08); transform: translateX(5px); }
        .rank-num { font-size: 18px; font-weight: 900; width: 25px; text-align: center; }
        .rank-1 { color: #ffdb00; } .rank-2 { color: #c0c0c0; } .rank-3 { color: #cd7f32; }
        .chart-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; }
    </style>

    <div class="section-header">
        <div class="section-title">Tin Tức Nổi Bật</div>
    </div>
    
    <div class="news-scroll-container">
        <div class="news-track">
            @if(isset($news_list) && count($news_list) > 0)
                
                @for ($i = 0; $i < 2; $i++)
                    @foreach($news_list as $news)
                    <div class="news-card">
                        <img src="{{ $news->image_url }}" class="news-img" onerror="this.src='https://via.placeholder.com/270x150'">
                        <div class="news-content">
                            <div class="news-big-title">{{ $news->title }}</div>
                            <div style="font-size: 12px; color: #888;">
                                {{ \Carbon\Carbon::parse($news->post_date)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endfor

            @else
                <p style="color:#666; padding-left: 10px; font-style: italic;">Đang cập nhật tin tức...</p>
            @endif
        </div>
    </div>

    <div class="section-header">
        <div class="section-title">Bảng Xếp Hạng</div>
    </div>
    
    <div class="charts-grid">
        <div class="chart-box" style="border-top: 3px solid #bd00ff;">
            <span class="chart-box-title">🔥 TOP THỊNH HÀNH</span>
            <div class="chart-list">
                @foreach ($chart_trending as $idx => $song)
                <div class="chart-row">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                    <div style="flex: 1; overflow: hidden;">
                        <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                        <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist_name }}</div>
                    </div>
                    <i class="fas fa-play" style="color: #00d1ff; font-size: 12px;"></i>
                </div>
                @endforeach
            </div>
        </div>

        <div class="chart-box" style="border-top: 3px solid #00d1ff;">
            <span class="chart-box-title">🇻🇳 TOP VIỆT NAM</span>
            <div class="chart-list">
                @foreach ($chart_vn as $idx => $song)
                <div class="chart-row">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                    <div style="flex: 1; overflow: hidden;">
                        <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                        <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist_name }}</div>
                    </div>
                    <i class="fas fa-play" style="color: #00d1ff; font-size: 12px;"></i>
                </div>
                @endforeach
            </div>
        </div>

        <div class="chart-box" style="border-top: 3px solid #ff0077;">
            <span class="chart-box-title">🌍 TOP QUỐC TẾ</span>
            <div class="chart-list">
                @foreach ($chart_usuk as $idx => $song)
                <div class="chart-row">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                    <div style="flex: 1; overflow: hidden;">
                        <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                        <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist_name }}</div>
                    </div>
                    <i class="fas fa-play" style="color: #00d1ff; font-size: 12px;"></i>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection