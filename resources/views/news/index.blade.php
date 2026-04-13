@extends('layouts.client')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .main-content {
        padding: 0 0 60px 0;
        background: #0d0f17;
        min-height: 100vh;
    }

    .header-banner {
        display: flex;
        align-items: flex-end;
        gap: 35px; 
        padding: 50px 50px 0 50px;
        margin-bottom: 50px;
    }

    .news-icon-box {
        width: 200px; 
        height: 200px;
        border-radius: 15px;
        background: linear-gradient(45deg, #00d1ff, #0055ff); 
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 35px rgba(0, 209, 255, 0.35);
        flex-shrink: 0;
    }

    .news-icon-box i { font-size: 90px; color: #fff; } /* Tăng từ 80px */

    .banner-info p.tag {
        font-size: 1rem; 
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 12px;
        color: #00d1ff;
    }

    .banner-info h1 { font-size: 4rem; font-weight: 900; margin: 0; line-height: 1; color: #fff; } 
    .banner-info p.desc { color: #ccc; font-size: 1.1rem; margin-top: 15px; } 

    .noti-page-container { max-width: 1100px; margin: 0 50px; } 

    .news-card {
        background: #1a1c26;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px; 
        padding: 25px; 
        display: flex;
        gap: 30px; 
        margin-bottom: 30px;
        transition: 0.3s;
        cursor: pointer;
        color: inherit;
        overflow: hidden;
    }

    .news-card:hover {
        border-color: #00d1ff;
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-8px);
        box-shadow: 0 15px 45px rgba(0,0,0,0.5);
    }

    .news-img {
        width: 280px;
        height: 270px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .news-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .news-subtitle { color: #00d1ff; font-size: 13px; margin-bottom: 10px; font-weight: bold; text-transform: uppercase; }
    .news-body h3 { margin: 0 0 12px 0; color: #fff; font-size: 24px; line-height: 1.3; font-weight: 800; } /* Tăng từ 22px */
    .news-text { color: #aaa; font-size: 15px; line-height: 1.7; margin-bottom: 20px; } /* Tăng từ 14px */

    .news-date { color: #666; font-size: 13px; margin-top: auto; display: block; }

    /* --- MODAL CHI TIẾT --- */
    .news-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.9); display: none;
        justify-content: center; align-items: center; z-index: 9999;
        backdrop-filter: blur(8px);
    }

    .news-modal-content {
        background: #1a1c26;
        width: 95%;
        max-width: 650px;
        max-height: 85vh;
        border-radius: 25px;
        position: relative;
        overflow-y: auto; 
        border: 1px solid #333;
        box-shadow: 0 25px 70px rgba(0,0,0,0.6);
        animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    
    .close-modal-btn {
        position: absolute; top: 15px; right: 20px; color: #fff; 
        font-size: 30px; cursor: pointer; z-index: 10; transition: 0.2s;
    }
    .close-modal-btn:hover { color: #ff4d4d; transform: rotate(90deg); }

    .modal-banner-img { width: 100%; height: 280px; object-fit: cover; }
    .modal-padding { padding: 30px; }
    .modal-full-text { color: #ccc; line-height: 1.8; font-size: 0.95rem; white-space: pre-wrap; }
</style>

<div class="main-content">
    {{-- PHẦN BANNER --}}
    <div class="header-banner">
        <div class="news-icon-box">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="banner-info">
            <p class="tag">Soundwave Official</p>
            <h1>Thông báo mới</h1>
        </div>
    </div>

    {{-- DANH SÁCH TIN TỨC --}}
    <div class="noti-page-container">
        @forelse($news_list as $news)
            <div class="news-card" onclick='openNewsModal(@json($news))'>
                <img src="{{ $news->image_url }}" class="news-img" onerror="this.src='{{ asset('image/default_news.png') }}'">
                
                <div class="news-body">
                    <div class="news-subtitle">{{ $news->subtitle }}</div>
                    <h3>{{ $news->title }}</h3>
                    <p class="news-text">
                        {{ \Illuminate\Support\Str::limit($news->content, 200, '...') }} {{-- Tăng giới hạn chữ hiển thị ngoài card --}}
                    </p>
                    
                    <div style="color: #00d1ff; font-weight: bold; font-size: 14px;">
                        Đọc toàn bộ bản tin <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                    </div>

                    <span class="news-date">
                        <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($news->post_date)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        @empty
            <div style="text-align: center; color: #666; padding: 120px;">
                <i class="fas fa-newspaper" style="font-size: 4rem; opacity: 0.2; margin-bottom: 20px;"></i>
                <p style="font-size: 1.2rem;">Chưa có thông báo nào mới.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- CẤU TRÚC MODAL CHI TIẾT --}}
<div id="newsModal" class="news-modal-overlay" onclick="closeNewsModal()">
    <div class="news-modal-content" onclick="event.stopPropagation()">
        <span class="close-modal-btn" onclick="closeNewsModal()">&times;</span>
        <img id="modalImg" class="modal-banner-img" src="">
        <div class="modal-padding">
            <div id="modalSubtitle" style="color:#00d1ff; font-weight:bold; margin-bottom:12px; font-size:15px; text-transform:uppercase; letter-spacing: 1px;"></div>
            <h2 id="modalTitle" style="margin-bottom:25px; font-size:32px; color: #fff; font-weight: 900; line-height: 1.2;"></h2>
            <div id="modalContent" class="modal-full-text"></div>
            <div style="margin-top:35px; color:#555; font-size:14px; border-top: 1px solid #2d2f3b; padding-top: 20px;">
                <i class="far fa-calendar-alt"></i> Bản tin cập nhật lúc: <span id="modalDate"></span>
            </div>
        </div>
    </div>
</div>

<script>
    function openNewsModal(news) {
        // Đổ dữ liệu
        document.getElementById('modalTitle').innerText = news.title;
        document.getElementById('modalSubtitle').innerText = news.subtitle;
        document.getElementById('modalContent').innerText = news.content;
        document.getElementById('modalImg').src = news.image_url;
        
        let date = new Date(news.post_date);
        document.getElementById('modalDate').innerText = date.toLocaleDateString('vi-VN');

        // Hiện Modal
        document.getElementById('newsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeNewsModal() {
        document.getElementById('newsModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeNewsModal();
    });
</script>
@endsection