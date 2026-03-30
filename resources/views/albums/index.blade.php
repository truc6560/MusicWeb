@extends('layouts.client')

@section('content')
<style>
    .header-banner {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 20px 0 40px 0;
    }

    .fav-img {
        width: 150px; height: 150px; border-radius: 12px;
        background: var(--primary-gradient);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 30px rgba(47, 172, 255, 0.3);
        flex-shrink: 0;
    }

    .fav-img i { font-size: 60px; color: #fff; }

    .album-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
        gap: 25px;
    }
</style>

<div class="header-banner">
    <div class="fav-img"><i class="fas fa-compact-disc"></i></div>
    <div class="banner-info">
        <p style="text-transform: uppercase; font-size: 12px; font-weight: bold; color: #00d1ff; margin-bottom: 8px;">Khám phá</p>
        <h1 style="font-size: 3rem; font-weight: 800; line-height: 1;">Albums</h1>
        <p style="color: var(--text-sub); margin-top: 10px;">Tuyển tập những album mới nhất dành cho bạn</p>
    </div>
</div>

<div class="album-container">
    @forelse($albums as $album)
        <x-album-card :album="$album" />
    @empty
        <div class="empty-message">Chưa có album nào được tìm thấy.</div>
    @endforelse
</div>
@endsection