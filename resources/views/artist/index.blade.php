@extends('layouts.client')

@section('content')
<style>
    .header-section { display: flex; justify-content: space-between; align-items: end; margin-bottom: 40px; padding: 0 20px; }
    .header-info h1 { font-size: 2.5rem; margin-bottom: 5px; color: #fff; }
    .header-info p { color: #aaa; }
    
    .search-box { position: relative; width: 300px; }
    .search-box input { width: 100%; padding: 10px 40px 10px 15px; border-radius: 20px; border: 1px solid #333; background: #1a1c26; color: #fff; outline: none; transition: border-color 0.3s; }
    .search-box input:focus { border-color: #bd00ff; }
    .search-box button { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background:none; border:none; cursor:pointer; color: #888; }
    
    .artist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 30px; padding: 0 20px 40px 20px; }
    
    .artist-card { background: #15171e; padding: 20px; border-radius: 10px; text-align: center; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; }
    .artist-card:hover { background: #1f222b; transform: translateY(-5px); border-color: #333; }
    .artist-img { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); border: 3px solid #15171e; background-color: #333; transition: border-color 0.3s; }
    .artist-card:hover .artist-img { border-color: #bd00ff; }
    .artist-name { font-size: 1.1rem; font-weight: 600; margin-bottom: 5px; color: #fff; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .artist-country { font-size: 0.9rem; color: #888; margin-bottom: 15px; }
    
    .btn-follow { padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: 0.2s; width: 100%; border: 1px solid #bd00ff; background: transparent; color: #bd00ff; }
    .btn-follow.active { background: #bd00ff; color: #fff; border: 1px solid #bd00ff; }
    .btn-follow:hover { transform: scale(1.05); }
</style>

<div class="main-content">
    {{-- Phần Header & Tìm kiếm --}}
    <div class="header-section">
        <div class="header-info">
            <h1>Nghệ Sĩ</h1>
            <p>Khám phá và theo dõi những nghệ sĩ bạn yêu thích</p>
        </div>
        
        <div class="search-box">
            <form action="{{ route('artists.index') }}" method="GET">
                <input type="text" name="q" placeholder="Tìm kiếm nghệ sĩ..." value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    {{-- Lưới nghệ sĩ hiển thị  --}}
    <div class="artist-grid">
        @forelse($artists as $artist)
            <x-artist-card :artist="$artist" />
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #888;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.2;"></i>
                <p>Không tìm thấy nghệ sĩ nào phù hợp với từ khóa "{{ request('q') }}"</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Script xử lý AJAX Toggle Follow --}}
<script>
    const currentUserId = {{ Auth::id() ?: 0 }};

    function toggleFollow(e, artistId) {
        e.stopPropagation(); // Ngăn việc bị chuyển hướng vào trang chi tiết khi bấm nút

        if (currentUserId === 0) {
            // Hiển thị modal yêu cầu đăng nhập (giống bản cũ của bạn)
            const modal = document.getElementById('loginRequestModal');
            if (modal) {
                modal.style.display = 'flex';
            } else {
                alert("Vui lòng đăng nhập để theo dõi nghệ sĩ.");
                window.location.href = "/login"; // Chuyển hướng đến trang đăng nhập nếu không có modal
            }
            return;
        }

        const btn = document.getElementById('btn-' + artistId);
        
        // Sử dụng Fetch API để gửi request tới route toggle-follow
        fetch("{{ route('artists.toggleFollow') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ artist_id: artistId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.action === 'liked') { 
                    btn.classList.add('active');
                    btn.innerHTML = '<i class="fas fa-check"></i> Đang theo dõi';
                } else {
                    btn.classList.remove('active');
                    btn.innerHTML = '<i class="fas fa-plus"></i> Theo dõi';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection