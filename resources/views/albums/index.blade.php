@extends('layouts.client')

@section('content')
<!-- Giữ nguyên link FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* --- GIỮ NGUYÊN TOÀN BỘ CSS CỦA BẠN --- */
.header-banner {
    display: flex;
    align-items: flex-end;
    gap: 30px;
    padding: 40px 40px 0 40px;
    margin-bottom: 40px;
}

.fav-img {
    width: 180px;
    height: 180px;
    border-radius: 12px;
    background: linear-gradient(45deg, #bd00ff, #00d1ff);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(189, 0, 255, 0.3);
    flex-shrink: 0;
}

.fav-img i {
    font-size: 80px;
    color: #fff;
}

.banner-info p.tag {
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 10px;
    color: #fff;
}

.banner-info h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 10px;
    line-height: 1;
    color: #fff;
}

.banner-info p.desc {
    color: #ccc;
    font-size: 1rem;
    margin: 0;
}

.album-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
    gap: 30px;
    padding: 40px;
}

.album-card {
    background-color: #1a1c26;
    border-radius: 12px;
    padding: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    text-align: left;
}

.album-card:hover {
    background-color: #262a36;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(189, 0, 255, 0.2);
}

/* Hiệu ứng hiện nút play khi hover card */
.album-card:hover .play-button {
    opacity: 1;
    transform: translateY(0);
}

.album-image-wrapper {
    position: relative;
    margin-bottom: 12px;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: 8px;
}

.album-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}

.album-card:hover .album-image {
    transform: scale(1.05);
}

.play-button {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 45px;
    height: 45px;
    background-color: #00d1ff; /* Thêm màu nền cho nút play để nổi bật */
    color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
}

.album-info a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: bold;
    display: block;
    margin-bottom: 4px;
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
}

.album-artist {
    color: #bbbbbb;
    font-size: 0.9rem;
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
}

.empty-message {
    grid-column: 1 / -1;
    text-align: center;
    color: #888888;
    padding: 50px;
    font-size: 1.2rem;
}
</style>

{{-- BỐ CỤC HEADER --}}
<div class="header-banner">
    <div class="fav-img">
        <i class="fas fa-compact-disc"></i>
    </div>
    <div class="banner-info">
        <p class="tag">Khám phá</p>
        <h1>Danh sách Albums</h1>
        <p class="desc">Tuyển tập những albums nổi bật nhất dành cho bạn</p>
    </div>
</div>

{{-- BỐ CỤC DANH SÁCH --}}
<div class="album-container">
    @forelse($albums as $album)
        <div class="album-card" onclick="window.location.href='{{ route('albums.show', $album->album_id) }}'">
            <div class="album-image-wrapper">
                <img class="album-image" src="{{ $album->image_url }}" alt="{{ $album->title }}">
                <div class="play-button"><i class="fas fa-play"></i></div>
            </div>

            <div class="album-info">
                <a>{{ $album->title }}</a>
                <div class="album-artist">{{ $album->artist->name }}</div>
            </div>
        </div>
    @empty
        <div class="empty-message">Chưa có album nào được tìm thấy.</div>
    @endforelse
</div>
@endsection