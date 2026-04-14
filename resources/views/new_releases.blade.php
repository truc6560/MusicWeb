<x-client-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .new-release-container {
        padding: 20px;
        background: linear-gradient(180deg, rgba(24, 17, 38, 0.5) 0%, #0d0f17 100%);
        min-height: 100vh;
    }

    .new-release-hero {
        position: relative;
        height: 220px; 
        display: flex;
        align-items: center; 
        padding: 0 50px; 
        background: linear-gradient(45deg, #181126, #bd00ff); 
        border-radius: 20px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        margin-top: 0; 
    }

    .hero-main-title {
        font-size: 4.5rem; 
        font-weight: 900;
        margin: 5px 0; 
        line-height: 1;
        letter-spacing: -3px;
        color: #fff;
        text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 209, 255, 0.2);
        color: #00d1ff; 
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .hero-subtitle {
        font-size: 15px;
        color: rgba(255,255,255,0.8);
        margin-bottom: 10px;
    }

    .hero-stats {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #fff;
        font-size: 13px;
    }

    .stat-dot {
        width: 4px;
        height: 4px;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
    }

    /* Table Styles */
    .table-wrapper {
        background: #14161f;
        border-radius: 16px;
        padding: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .new-song-table { 
        width: 100%; 
        border-collapse: collapse; 
    }

    .new-song-table th { 
        text-align: left; 
        color: #555; 
        padding: 15px; 
        font-size: 12px; 
        letter-spacing: 1px;
        border-bottom: 1px solid #2d2f3b; 
    }
    
    .song-item-row { 
        cursor: pointer; 
        transition: 0.2s; 
    }

    .song-item-row:hover { 
        background: rgba(255, 255, 255, 0.05); 
    }
    
    .song-info-cell { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        padding: 12px 15px; 
    }

    .song-thumb-mini { 
        width: 45px; 
        height: 45px; 
        border-radius: 6px; 
        object-fit: cover; 
    }

    .song-name-text {
        display: block;
        color: #fff;
        font-weight: 600;
    }

    .artist-name-text {
        display: block;
        color: #888;
        font-size: 12px;
        margin-top: 2px;
    }

    .rank-cell { 
        color: rgba(255,255,255,0.2); 
        font-size: 18px; 
        font-weight: 700; 
        font-style: italic;
        text-align: center;
    }

    .date-cell { 
        color: #666; 
        font-family: 'Courier New', Courier, monospace;
    }

    @media (max-width: 768px) {
        .hero-main-title { font-size: 3rem; }
        .new-release-hero { height: auto; padding: 40px 25px; }
    }
</style>

<div class="new-release-container">
    {{-- HERO SECTION --}}
    <div class="new-release-hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-bolt"></i> Cập nhật mới nhất
            </div>
            <h1 class="hero-main-title">Phát Hành Mới</h1>
            <p class="hero-subtitle">Khám phá những giai điệu vừa ra mắt trên hệ thống Soundwave</p>
            
            <div class="hero-stats">
                <span class="stat-item"><i class="fas fa-music"></i> {{ count($songs) }} bài hát</span>
                <span class="stat-dot"></span>
                <span class="stat-item"><i class="far fa-calendar-alt"></i> {{ date('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- BẢNG DANH SÁCH BÀI HÁT --}}
    <div class="table-wrapper">
        <table class="new-song-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 60px;">#</th>
                    <th>BÀI HÁT</th>
                    <th width="200" style="text-align: right; padding-right: 25px;">NGÀY PHÁT HÀNH</th>
                </tr>
            </thead>
            <tbody>
                @forelse($songs as $index => $song)
                    @php
                        // Chuẩn hóa dữ liệu tương tự bản PHP cũ
                        $release_date = \Carbon\Carbon::parse($song->release_date)->format('d/m/Y');
                        $img = $song->image_url ?: asset('image/default_cover.png');
                        
                        // Luôn dùng stream endpoint để xử lý tốt tên file có dấu/ký tự đặc biệt.
                        $src = route('song.stream', ['id' => $song->song_id]);
                    @endphp
                    <tr class="song-item-row"
                        data-id="{{ $song->song_id }}"
                        data-title="{{ $song->title }}"
                        data-artist="{{ $song->artist->name }}"
                        data-src="{{ $src }}"
                        data-cover="{{ $img }}">

                        {{-- Rank cell định dạng 01, 02... --}}
                        <td class="rank-cell">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        
                        <td>
                            <div class="song-info-cell">
                                <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #041018; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-play" style="font-size: 11px;"></i>
                                </button>
                                <a href="{{ route('song.details', ['id' => $song->song_id]) }}" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                                    <img src="{{ $img }}" class="song-thumb-mini">
                                </a>
                                <div>
                                    <span class="song-name-text">{{ $song->title }}</span>
                                    <span class="artist-name-text">{{ $song->artist->name }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="date-cell" style="text-align:right; padding-right:25px;">
                            {{ $release_date }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:40px; color:#666;">
                            Chưa có bài hát mới.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-client-layout>