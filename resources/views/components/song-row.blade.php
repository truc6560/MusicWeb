@props(['song', 'index', 'albumArtist', 'albumImage'])

@php
    // Chuyển đổi thời lượng từ giây sang định dạng phút:giây
    $duration = sprintf("%d:%02d", floor($song->duration / 60), $song->duration % 60);
    
    // Xử lý đường dẫn file nhạc chuẩn Laravel
    $audioPath = asset('audio/' . ltrim($song->audio_file, '/'));
@endphp

<tr class="song-item-row" 
    data-id="{{ $song->song_id }}" 
    data-title="{{ $song->title }}" 
    data-artist="{{ $albumArtist }}" 
    data-src="{{ $audioPath }}"
    data-cover="{{ $albumImage }}">

    {{-- Số thứ tự bài hát --}}
    <td style="color:#888">{{ $index }}</td>

    <td>
        <div class="song-info-flex">
            {{-- Ảnh nhỏ bài hát --}}
            <img src="{{ $albumImage }}" class="song-thumb-small" alt="Thumb">
            <div>
                {{-- Tiêu đề bài hát --}}
                <div class="song-title-row">{{ $song->title }}</div>
                {{-- Tên nghệ sĩ --}}
                <div class="song-artist-row">{{ $albumArtist }}</div>
            </div>
        </div>
    </td>

    {{-- Thời lượng bài hát --}}
    <td style="text-align:right">{{ $duration }}</td>

    {{-- Nút Like (Yêu thích) --}}
    <td style="text-align:center">
        <button class="btn-like {{ $song->is_liked ? 'liked' : '' }}" 
                onclick="toggleLike(event, {{ $song->song_id }})">
            <i class="fas fa-heart"></i>
        </button>
    </td>
</tr>