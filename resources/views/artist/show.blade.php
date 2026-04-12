<x-client-layout>
<style>
    .song-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .song-table th { text-align: left; color: #888; padding: 12px 20px; border-bottom: 1px solid #2d2f3b; font-size: 14px; font-weight: normal; }
    .song-table td { padding: 10px 20px; border-bottom: 1px solid #1f212d; color: #ddd; vertical-align: middle; }
    .song-table tr { cursor: pointer; transition: 0.2s; }
    .song-table tr:hover { background-color: rgba(255,255,255,0.05); }
    
    .song-info-flex { display: flex; align-items: center; gap: 15px; }
    .song-thumb-small { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
    
    .song-title-row { font-weight: 600; color: #fff; }
    .song-artist-row { font-size: 0.85rem; color: #888; }
    
    .verified-badge { font-size: 12px; font-weight: bold; color: #bd00ff; text-transform: uppercase; letter-spacing: 1px; }
    .artist-bio { color: #aaa; max-width: 650px; line-height: 1.6; margin-top: 10px; }
    
    .back-link { color: #bd00ff; text-decoration: none; font-weight: 700; transition: 0.3s; }
    .back-link:hover { color: #d633ff; text-shadow: 0 0 10px rgba(189, 0, 255, 0.4); }

    .artist-follow-btn {
        margin-top: 18px;
        border: 1px solid #bd00ff;
        background: transparent;
        color: #ffffff;
        font-weight: 700;
        border-radius: 999px;
        padding: 10px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .artist-follow-btn:hover {
        background: rgba(189, 0, 255, 0.2);
    }
    .artist-follow-btn.liked {
        border-color: #00d1ff;
        background: rgba(0, 209, 255, 0.15);
    }
</style>

<div class="main-content" style="padding: 20px;">
    
    {{-- Nút quay lại danh sách --}}
    <a href="{{ route('artists.index') }}" class="back-link">
        <i class="fas fa-chevron-left"></i> TRỞ LẠI DANH SÁCH
    </a>
    
    {{-- Header thông tin nghệ sĩ --}}
    <div style="display:flex; align-items:flex-end; gap:35px; margin: 40px 0;">
        <img src="{{ $artist->image_url ?: asset('image/default_artist.png') }}" 
             style="width:230px; height:230px; object-fit:cover; border-radius:50%; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        
        <div>
            <span class="verified-badge">
                <i class="fas fa-check-circle"></i> Nghệ sĩ được xác minh
            </span>
            <h1 style="font-size: 60px; margin: 10px 0; font-weight: 800; letter-spacing: -2px;">
                {{ $artist->name }}
            </h1>
            <div class="artist-bio">
                {{ $artist->bio ?: 'Chưa có thông tin tiểu sử cho nghệ sĩ này.' }}
            </div>

            <button class="artist-follow-btn btn-like-artist {{ $isFollowed ? 'liked' : '' }}"
                    data-id="{{ $artist->artist_id }}"
                    type="button">
                @if($isFollowed)
                    <i class="fas fa-check"></i> Đang theo dõi
                @else
                    <i class="fas fa-plus"></i> Theo dõi
                @endif
            </button>
        </div>
    </div>

    {{-- Danh sách bài hát --}}
    <div style="margin-top: 50px;">
        <h2 style="font-size: 1.5rem; margin-bottom: 20px;">Bài hát phổ biến</h2>
        
        <table class="song-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Bài hát</th>
                    <th style="text-align: right;"><i class="far fa-clock"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($songs as $index => $song)
                    @php
                        // Định dạng thời gian từ giây sang mm:ss
                        $min = floor($song->duration / 60);
                        $sec = $song->duration % 60;
                        $time = sprintf("%d:%02d", $min, $sec);
                        
                        // Đường dẫn audio (giống logic cũ của bạn)
                        $audioPath = asset('audio/' . ltrim($song->audio_file, '/'));
                    @endphp

                    {{-- Row bài hát với đầy đủ data attributes cho Player JavaScript --}}
                    <tr class="song-item-row"
                        data-id="{{ $song->song_id }}"
                        data-title="{{ $song->title }}"
                        data-artist="{{ $artist->name }}"
                        data-src="{{ $audioPath }}"
                        data-cover="{{ $song->image_url ?: asset('image/default_cover.png') }}">
                        
                        <td style="color:#888; width: 50px;">{{ $index + 1 }}</td>
                        <td>
                            <div class="song-info-flex">
                                <img src="{{ $song->image_url ?: asset('image/default_cover.png') }}" class="song-thumb-small">
                                <div>
                                    <div class="song-title-row">{{ $song->title }}</div>
                                    <div class="song-artist-row">{{ $artist->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:right; color: #888;">{{ $time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px; color: #666;">
                            Nghệ sĩ này hiện chưa có bài hát nào được đăng tải.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-client-layout>