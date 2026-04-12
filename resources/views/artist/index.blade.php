<x-client-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* --- COPY HOÀN TOÀN TỪ ALBUMS --- */
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

.artist-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
    gap: 30px;
    padding: 40px;
}

.artist-card {
    display: block;
    background-color: #1a1c26;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    text-align: center; /* Nghệ sĩ thường căn giữa tên */
    text-decoration: none;
    color: inherit;
}

.artist-card:hover {
    background-color: #262a36;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(189, 0, 255, 0.2);
}

.artist-image-wrapper {
    position: relative;
    margin-bottom: 15px;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: 50%; /* Khác Album: Nghệ sĩ để hình tròn */
    border: 3px solid #1a1c26;
    transition: border-color 0.3s;
}

.artist-card:hover .artist-image-wrapper {
    border-color: #bd00ff;
}

.artist-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}

.artist-card:hover .artist-image {
    transform: scale(1.05);
}

.artist-info a {
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

.artist-subtitle {
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
        <i class="fas fa-microphone-lines"></i> {{-- Icon Micro cho Nghệ sĩ --}}
    </div>
    <div class="banner-info">
        <p class="tag">Khám phá</p>
        <h1>Danh sách Nghệ sĩ</h1>
        <p class="desc">Khám phá những gương mặt tài năng nhất dành cho bạn</p>
    </div>
</div>

{{-- BỐ CỤC DANH SÁCH --}}
<div class="artist-container">
    @forelse($artists as $artist)
        <a class="artist-card" href="{{ route('artists.show', $artist->artist_id) }}">
            <div class="artist-image-wrapper">
                <img class="artist-image" src="{{ $artist->image_url ?: asset('image/default_artist.png') }}" alt="{{ $artist->name }}">
            </div>

            <div class="artist-info">
                <span>{{ $artist->name }}</span>
                <div class="artist-subtitle">{{ $artist->country ?: 'Nghệ sĩ' }}</div>
            </div>
        </a>
    @empty
        <div class="empty-message">Chưa có nghệ sĩ nào được tìm thấy.</div>
    @endforelse
</div>
</x-client-layout>