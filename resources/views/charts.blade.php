@extends('layouts.client')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .chart-container {
        padding: 20px;
        background-color: #0d0f17;
        min-height: 100vh;
    }

    /* Hero Banner cho Bảng xếp hạng */
    .chart-hero {
        height: 220px;
        display: flex;
        align-items: center;
        padding: 0 50px;
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); /* Màu cam hồng nổi bật */
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
    }

    .hero-content h1 {
        font-size: 4.5rem;
        font-weight: 900;
        letter-spacing: -3px;
        color: #fff;
        margin: 0;
        line-height: 1;
        text-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .ranking-table-wrapper {
        background: #14161f;
        border-radius: 16px;
        padding: 10px;
    }

    .ranking-table { 
        width: 100%; border-collapse: collapse; 
    }
    
    .ranking-table th { 
        text-align: left; color: #555; padding: 15px; 
        font-size: 12px; border-bottom: 1px solid #2d2f3b; 
    }

    .song-item-row { 
        cursor: pointer; transition: 0.2s; border-bottom: 1px solid rgba(255,255,255,0.02); 
    }
    .song-item-row:hover { 
        background: rgba(255, 255, 255, 0.05); 
    }
    
    .rank-col { 
        width: 80px; text-align: center; 
        font-size: 24px; font-weight: 900; 
        font-style: italic; color: rgba(255,255,255,0.1); 
    }
    .rank-1 { color: #ffdb00 !important; text-shadow: 0 0 15px rgba(255, 219, 0, 0.5); font-size: 32px; }
    .rank-2 { color: #c0c0c0 !important; font-size: 28px; }
    .rank-3 { color: #cd7f32 !important; font-size: 26px; }

    .song-info-flex { display: flex; align-items: center; gap: 15px; padding: 10px 0; }
    .song-cover-mini { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; }
    
    .song-name-text { font-weight: 600; color: #fff; display: block; font-size: 15px; }
    .artist-name-text { font-size: 13px; color: #888; }
    
    .plays-text { color: #00d1ff; font-weight: 700; font-family: 'Courier New', monospace; font-size: 16px; }
</style>

<div class="chart-container">
    {{-- BANNER ĐẦU TRANG --}}
    <div class="chart-hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-crown"></i> Hot Trending
            </div>
            <h1>Bảng Xếp Hạng</h1>
            <p style="color: rgba(255,255,255,0.8); margin-top: 10px; font-weight: 500;">
                Top 10 bài hát có lượt nghe cao nhất trên Soundwave tuần này
            </p>
        </div>
    </div>

    {{-- BẢNG XẾP HẠNG --}}
    <div class="ranking-table-wrapper">
        <table class="ranking-table">
            <thead>
                <tr>
                    <th style="text-align: center;">HẠNG</th>
                    <th>BÀI HÁT</th>
                    <th style="text-align: right; padding-right: 20px;">LƯỢT NGHE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($songs as $index => $song)
                    @php
                        $rank = $index + 1;
                        $rankClass = ($rank <= 3) ? "rank-$rank" : "";
                        $img = $song->image_url ?: asset('image/default_cover.png');
                    @endphp
                    <tr class="song-item-row"
                        data-id="{{ $song->song_id }}"
                        data-title="{{ $song->title }}"
                        data-artist="{{ $song->artist->name }}"
                        data-src="{{ $song->src }}"
                        data-cover="{{ $img }}">

                        {{-- Cột Thứ hạng --}}
                        <td class="rank-col {{ $rankClass }}">
                            {{ $rank }}
                        </td>

                        {{-- Cột Thông tin bài hát --}}
                        <td>
                            <div class="song-info-flex">
                                <img src="{{ $img }}" class="song-cover-mini">
                                <div>
                                    <span class="song-name-text">{{ $song->title }}</span>
                                    <span class="artist-name-text">{{ $song->artist->name }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Lượt nghe --}}
                        <td style="text-align: right; padding-right: 20px;">
                            <span class="plays-text">{{ number_format($song->plays, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:50px; color:#666;">
                            Chưa có dữ liệu xếp hạng.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script để chơi nhạc khi click vào hàng --}}
<script>
    document.querySelectorAll('.song-item-row').forEach(row => {
        row.addEventListener('click', function() {
            if (typeof playSong === "function") {
                playSong({
                    id: this.dataset.id,
                    title: this.dataset.title,
                    artist: this.dataset.artist,
                    src: this.dataset.src,
                    cover: this.dataset.cover
                });
            }
        });
    });
</script>
@endsection