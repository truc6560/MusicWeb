@props(['song', 'index', 'albumArtist', 'albumImage'])

@php
    // Chuyển đổi thời lượng từ giây sang định dạng phút:giây
    $duration = sprintf("%d:%02d", floor($song->duration / 60), $song->duration % 60);
    
    // Luôn phát qua stream endpoint để tránh lỗi tên file Unicode trên Windows
    $audioPath = route('song.stream', ['id' => $song->song_id]);
    $songCover = $song->image_url ?: $albumImage;
@endphp

<tr class="song-item-row" 
    data-id="{{ $song->song_id }}" 
    data-title="{{ $song->title }}" 
    data-artist="{{ $albumArtist }}" 
    data-src="{{ $audioPath }}"
    data-cover="{{ $songCover }}"
    data-artist-id="{{ $song->artist_id }}"
    data-album-id="{{ $song->album_id ?? '' }}">

    {{-- Số thứ tự bài hát --}}
    <td style="color:#888">{{ $index }}</td>

    <td>
        <div class="song-info-flex">
            <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #041018; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-play" style="font-size: 11px;"></i>
            </button>
            {{-- Ảnh nhỏ bài hát --}}
            <a href="{{ route('song.details', ['id' => $song->song_id]) }}" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                <img src="{{ $songCover }}" class="song-thumb-small" alt="Thumb">
            </a>
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

</tr>