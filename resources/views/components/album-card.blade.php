@props(['album'])

<div class="album-card" onclick="window.location.href='{{ route('albums.show', $album->album_id) }}'">
    <div class="album-image-wrapper">
        {{-- Hiển thị ảnh bìa Album --}}
        <img src="{{ $album->image_url }}" class="album-image" alt="{{ $album->title }}">
        
        {{-- Nút Play xuất hiện khi Hover --}}
        <div class="play-button">
            <i class="fas fa-play" style="color: #000;"></i>
        </div>
    </div>
    
    <div class="album-info">
        {{-- Tên Album --}}
        <a>{{ $album->title }}</a>
        
        {{-- Tên nghệ sĩ --}}
        <div class="album-artist">{{ $album->artist->name }}</div>
    </div>
</div>