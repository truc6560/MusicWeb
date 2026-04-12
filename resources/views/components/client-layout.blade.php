@props(['title' => 'Lumina Audio - SOUNDWAVE'])
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon2.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* 1. KHỞI TẠO BIẾN VÀ RESET */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --player-bg: rgba(18, 20, 29, 0.98);
            --primary-gradient: linear-gradient(90deg, #2facff 0%, #b04aff 100%);
            --text-main: #ffffff;
            --text-sub: #a0a0a0;
            --border-color: rgba(255, 255, 255, 0.05);
            --bg-dark: #0b0e14;
            --bg-panel: #161822;
        }
        
        /* KHÓA CHIỀU CAO WEB - CHỈ CUỘN BÊN TRONG */
        body { 
            background-color: var(--bg-dark); color: var(--text-main); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            height: 100vh; overflow: hidden; display: flex; flex-direction: column; 
        }

        /* 2. HEADER */
        .header { 
            height: 70px; display: flex; justify-content: space-between; align-items: center; 
            padding: 0 30px; background: var(--bg-panel); border-bottom: 1px solid var(--border-color); flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; gap: 30px; }
        .logo-neon { color: #00e5ff; font-weight: bold; font-size: 20px; text-shadow: 0 0 10px rgba(0,229,255,0.8); display: flex; align-items: center; gap: 10px;}
        .main-nav a { color: var(--text-sub); text-decoration: none; margin-right: 20px; font-weight: 500; font-size: 14px; transition: 0.3s; }
        .main-nav a.active, .main-nav a:hover { color: #00d1ff; border-bottom: 2px solid #00d1ff; padding-bottom: 5px; }
        
        .search-bar {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 20px;
            width: 350px;
            display: flex;
            align-items: center;
            position: relative;
        }
        .search-bar input {
            border: none;
            background: transparent;
            color: #fff;
            margin-left: 10px;
            width: 100%;
            outline: none;
        }
        .search-suggestions {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            right: 0;
            background: #11141d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            z-index: 50;
            display: none;
        }
        .search-suggestion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            text-decoration: none;
            color: #fff;
            transition: background 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }
        .search-suggestion-item:last-child {
            border-bottom: none;
        }
        .search-suggestion-item:hover {
            background: rgba(0, 209, 255, 0.08);
        }
        .search-suggestion-cover {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .search-suggestion-meta {
            min-width: 0;
            flex: 1;
        }
        .search-suggestion-title {
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .search-suggestion-subtitle {
            font-size: 12px;
            color: var(--text-sub);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }
        .search-suggestion-type {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #00d1ff;
            margin-left: 8px;
            flex-shrink: 0;
        }
        .search-suggestion-empty {
            padding: 12px 14px;
            color: var(--text-sub);
            font-size: 13px;
        }

        /* NÚT BẤM & USER CONTROL */
        .btn-action { padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 500; font-size: 13px; cursor: pointer; }
        .btn-style-gradient { background: var(--primary-gradient); color: #fff !important; border: none; }
        .btn-style-outline { background: transparent; border: 1.5px solid #ffffff; color: #ffffff !important; }
        .guest-actions { display: flex; gap: 15px; }
        
        .user-profile { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.1); padding: 5px 15px 5px 5px; border-radius: 50px; cursor: pointer; }
        .user-profile img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .user-profile span { font-size: 13px; font-weight: 600; }

        /* 3. BỐ CỤC 3 CỘT (Cực kỳ quan trọng) */
        .main-container { 
            flex: 1; display: flex; overflow: hidden; padding-bottom: 80px; /* Chừa chỗ cho Player */
        }
        
        /* Cột Trái (Menu) */
        .sidebar-left { 
            width: 240px; background: var(--bg-panel); border-right: 1px solid var(--border-color); 
            padding: 20px; flex-shrink: 0; overflow-y: auto; 
        }
        .menu-group h3 { color: var(--text-sub); font-size: 12px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;}
        .menu-item { display: block; padding: 12px 15px; color: var(--text-sub); text-decoration: none; margin-bottom: 5px; border-radius: 8px; font-size: 14px; transition: 0.3s; }
        .menu-item.active, .menu-item:hover { background: rgba(0, 209, 255, 0.1); color: #00d1ff; font-weight: bold; border-left: 3px solid #00d1ff; }

        /* Cột Giữa (Nội dung thay đổi) */
        .content-area { 
            flex: 1; overflow-y: auto; padding: 30px 40px; 
            background: radial-gradient(circle at 10% 10%, #1a1c29 0%, #0d0f17 90%); 
        }

        /* Cột Phải (Lyrics) */
        .sidebar-right { 
            width: 300px; background: var(--bg-panel); border-left: 1px solid var(--border-color); 
            padding: 20px; flex-shrink: 0; overflow-y: auto; text-align: center;
        }
        .song-cover { width: 100%; aspect-ratio: 1/1; border-radius: 12px; object-fit: cover; margin-bottom: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        #lyricsBox { color: var(--text-sub); font-size: 14px; line-height: 2; text-align: left; margin-top: 20px; padding-bottom: 20px;}
        .lyrics-line { display: block; margin-bottom: 10px; transition: 0.3s; }
        .lyrics-line.active { color: #00d1ff; font-size: 16px; font-weight: bold; }

        /* 4. PLAYER THANH NHẠC DƯỚI ĐÁY */
        footer { height: 80px; background: #1a1c26; border-top: 1px solid #2d2f3b; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; }
        .player-bar { height: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        
        .player-left { width: 25%; display: flex; align-items: center; }
        .player-song-img { width: 50px; height: 50px; border-radius: 8px; margin-right: 15px; object-fit: cover; }
        
        .player-center { flex: 1; display: flex; flex-direction: column; align-items: center; max-width: 600px; }
        .player-controls { display: flex; align-items: center; gap: 25px; margin-bottom: 8px; }
        .control-icon { color: var(--text-sub); cursor: pointer; font-size: 16px; }
        .control-icon:hover { color: #fff; }
        .play-btn-wrapper { width: 42px; height: 42px; background: var(--primary-gradient); border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #000; cursor: pointer; }
        
        .progress-area { display: flex; align-items: center; width: 100%; gap: 12px; }
        .progress-container { flex: 1; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; cursor: pointer; position: relative; }
        .progress-bar-fill { height: 100%; background: var(--primary-gradient); border-radius: 10px; width: 30%; }
        
        .player-right { width: 25%; display: flex; justify-content: flex-end; align-items: center; gap: 10px; }
        .volume-slider { width: 100px; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .volume-fill { height: 100%; background: var(--primary-gradient); border-radius: 10px; width: 70%; }

        /* 5. THANH THỂ LOẠI (GENRE NAV) - BẢN NHỎ GỌN */
        .genre-nav {
            display: flex; gap: 20px; padding: 12px 30px; 
            background: var(--bg-dark); border-bottom: 1px solid var(--border-color);
            overflow-x: auto; flex-shrink: 0;
            justify-content: center;
        }
        .genre-nav::-webkit-scrollbar { display: none; } /* Ẩn thanh cuộn cho mượt */
        
        .genre-card {
            display: flex; 
            flex-direction: row; /* Xếp icon và chữ nằm ngang */
            align-items: center; 
            gap: 10px;
            padding: 10px 20px; /* Tạo hình chữ nhật */
            background: #1a1c26;
            border-radius: 10px; /* Bo góc nhẹ cho hình chữ nhật */
            border: 1px solid rgba(255,255,255,0.05);
            cursor: pointer; 
            transition: 0.3s;
            height: 45px; /* Ép chiều cao thấp xuống để nhường chỗ cho Center */
        }

        .genre-card:hover {
            background: var(--primary-gradient); 
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(189,0,255,0.3);
        }

        .genre-icon {
            font-size: 16px; 
            color: var(--text-sub);
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .genre-name { 
            font-size: 13px; 
            font-weight: 600; 
            color: var(--text-sub); 
            white-space: nowrap; /* Không cho rớt dòng */
            transition: 0.3s; 
        }

        /* Đổi màu chữ và icon sang trắng khi hover */
        .genre-card:hover .genre-icon, 
        .genre-card:hover .genre-name { 
            color: #fff; 
        }

        ::-webkit-scrollbar {
            width: 6px; 
            height: 6px; 
        }

        ::-webkit-scrollbar-track {
            background: transparent; 
        }

        /* 1. Mặc định: Thanh cuộn hoàn toàn trong suốt (Tàng hình) */
        ::-webkit-scrollbar-thumb {
            background: transparent; 
            border-radius: 10px; 
            transition: background 0.3s ease; /* Tạo độ mượt khi hiện lên */
        }

        /* 2. Khi rê chuột vào khu vực web: Hiện lên mờ mờ */
        *:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15); 
        }

        /* 3. Khi rê chuột TRỰC TIẾP vào thanh cuộn: Sáng màu xanh xịn xò */
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 209, 255, 0.5) !important; 
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <div class="logo-neon"><span class="music-icon">🎵</span> SOUNDWAVE</div>
            <nav class="main-nav">
                <a href="{{ route('client.home') }}" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a>
                <a href="{{ route('albums.index') }}" class="{{ request()->is('albums*') ? 'active' : '' }}">Albums</a>
                <a href="{{ route('charts') }}" class="{{ request()->is('charts') ? 'active' : '' }}">Bảng xếp hạng</a>
                <a href="{{ route('new_releases') }}" class="{{ request()->is('new-releases') ? 'active' : '' }}">Phát hành mới</a>
                <a href="{{ route('artists.index') }}" class="{{ request()->is('artists*') ? 'active' : '' }}">Nghệ sĩ</a>
            </nav>
        </div>

        <div class="search-bar">
            <span>🔍</span>
            <form id="siteSearchForm" style="flex: 1; position: relative;">
                <input id="siteSearchInput" type="text" placeholder="Tìm kiếm bài hát, nghệ sĩ..." autocomplete="off">
                <div id="searchSuggestions" class="search-suggestions" aria-live="polite"></div>
            </form>
        </div>

        <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
            @auth
                <div class="user-profile">
                    <img src="{{ auth()->user()->avatar_url ?? asset('image/default_user.png') }}" alt="User">
                    <span>{{ auth()->user()->username ?? 'Người dùng' }}</span>
                    <i class="fas fa-caret-down" style="color: var(--text-sub); margin-left: 5px;"></i>
                </div>
                <i class="fas fa-bell" style="color: #ffb700; cursor: pointer;"></i>
            @else
                <div class="guest-actions">
                    <a href="#" class="btn-action btn-style-outline">Đăng ký</a>
                    <a href="#" class="btn-action btn-style-gradient">Đăng nhập</a>
                </div>
            @endauth
        </div>
    </header>

    <nav class="genre-nav">
        @php
            $fakeGenres = [
                ['name' => 'Pop', 'icon' => '<i class="fas fa-microphone-alt"></i>'], 
                ['name' => 'Ballad', 'icon' => '<i class="fas fa-guitar"></i>'],
                ['name' => 'Rock', 'icon' => '<i class="fas fa-bolt"></i>'], 
                ['name' => 'Hip Hop', 'icon' => '<i class="fas fa-compact-disc"></i>'],
                ['name' => 'R&B', 'icon' => '<i class="fas fa-heart"></i>'], 
                ['name' => 'EDM', 'icon' => '<i class="fas fa-headphones"></i>'],
                ['name' => 'Acoustic', 'icon' => '<i class="fas fa-leaf"></i>'], 
                ['name' => 'Indie', 'icon' => '<i class="fas fa-feather-alt"></i>'],
                ['name' => 'Jazz', 'icon' => '<i class="fas fa-wine-glass-alt"></i>'], 
                ['name' => 'Classical', 'icon' => '<i class="fas fa-music"></i>'],
                ['name' => 'K Pop', 'icon' => '<i class="fas fa-crown"></i>'], 
                ['name' => 'V Pop', 'icon' => '<i class="fas fa-star"></i>']
            ];
        @endphp

        @foreach($fakeGenres as $genre)
        <a href="#" style="text-decoration: none;">
            <div class="genre-card">
                <div class="genre-icon">{!! $genre['icon'] !!}</div>
                <div class="genre-name">{{ $genre['name'] }}</div>
            </div>
        </a>
        @endforeach
    </nav>

    <div class="main-container">
        
        <aside class="sidebar-left">
            <div class="menu-group">
                <h3>MUSIC</h3>
                @auth
                    <a href="{{ route('playlist.index') }}" class="menu-item {{ request()->routeIs('playlist.*') ? 'active' : '' }}">Playlist</a>
                    <a href="{{ route('library.songs') }}" class="menu-item {{ request()->routeIs('library.songs') ? 'active' : '' }}">Favorite Songs</a>
                    <a href="{{ route('library.artists') }}" class="menu-item {{ request()->routeIs('library.artists') ? 'active' : '' }}">Favorite Artists</a>
                    <a href="{{ route('library.history') }}" class="menu-item {{ request()->routeIs('library.history') ? 'active' : '' }}">Listening History</a>
                @else
                    <a href="#" class="menu-item" onclick="event.preventDefault(); alert('Vui lòng đăng nhập để dùng Playlist.');">Playlist</a>
                    <a href="#" class="menu-item" onclick="event.preventDefault(); alert('Vui lòng đăng nhập để xem bài hát yêu thích.');">Favorite Songs</a>
                    <a href="#" class="menu-item" onclick="event.preventDefault(); alert('Vui lòng đăng nhập để xem nghệ sĩ yêu thích.');">Favorite Artists</a>
                    <a href="{{ route('library.history') }}" class="menu-item {{ request()->routeIs('library.history') ? 'active' : '' }}">Listening History</a>
                @endauth
            </div>
            <div class="menu-group" style="margin-top: 30px;">
                <h3>DANH SÁCH CHỜ</h3>
                <div style="color: #666; font-size: 13px; padding-left: 15px;">Chưa có bài hát nào...</div>
            </div>
        </aside>

        <main class="content-area">
            {{ $slot }}
        </main>

        <aside class="sidebar-right">
            <h3 style="color: #fff; font-size: 16px; margin-bottom: 20px; text-transform: uppercase;">Lời Bài Hát</h3>
            <img id="rightCover" class="song-cover" src="{{ asset('image/default-cover.jpg') }}">
            <h4 id="rightTitle" style="color: #fff; margin-bottom: 5px;">Chưa chọn bài hát</h4>
            <p id="rightArtist" style="color: var(--text-sub); font-size: 13px;">--</p>
            
            <div id="lyricsBox">
                <span class="lyrics-line">Hãy phát một bài hát</span>
                <span class="lyrics-line">để xem lời tại đây nhé!</span>
            </div>
        </aside>

    </div>

    <footer>
        <div class="player-bar">
            <div class="player-left">
                <a id="nowCoverLink" href="#" style="display: inline-flex; line-height: 0; text-decoration: none;">
                    <img id="nowCover" src="{{ asset('image/default-cover.jpg') }}" class="player-song-img">
                </a>
                <div>
                    <div id="now-title" style="color: #fff; font-size: 14px; font-weight: bold;">Tên bài hát</div>
                    <div id="now-artist" style="color: var(--text-sub); font-size: 12px;">Tên nghệ sĩ</div>
                </div>
                <button type="button" class="player-like-btn" style="background: none; border: none; margin-left: 20px; cursor: pointer;">
                    <i class="far fa-heart" style="color: var(--text-sub);"></i>
                </button>
                @auth
                    @php
                        $playerPlaylists = auth()->user()->playlists()->orderBy('name')->get();
                        $playerPlaylistsData = $playerPlaylists->map(function ($playlist) {
                            return [
                                'playlist_id' => $playlist->playlist_id,
                                'name' => $playlist->name,
                            ];
                        })->values();
                    @endphp
                    <div class="player-playlist-wrap" style="position: relative; margin-left: 10px;">
                        <button type="button" id="playerPlaylistBtn" class="player-playlist-btn" style="border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.08); color: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 12px; border-radius: 999px; font-weight: 800; font-size: 12px; white-space: nowrap;">
                            <i class="fas fa-plus"></i>
                            <span>Playlist</span>
                        </button>
                        <div id="playerPlaylistPopup" style="display:none; position: absolute; right: 0; bottom: calc(100% + 12px); width: 320px; background: #11141d; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.45); overflow: hidden; z-index: 60;">
                            <div style="padding: 12px 14px; font-size: 12px; font-weight: 800; color: #8f95af; text-transform: uppercase; letter-spacing: .8px; border-bottom: 1px solid rgba(255,255,255,0.05);">Thêm vào playlist</div>
                            <div style="padding: 10px 12px; max-height: 240px; overflow: auto;" id="playerPlaylistList">
                                @forelse($playerPlaylists as $playlist)
                                    <button type="button" class="player-playlist-item" data-playlist-id="{{ $playlist->playlist_id }}" style="width: 100%; text-align: left; padding: 12px 12px; background: transparent; border: none; color: #fff; cursor: pointer; display: block; border-radius: 10px;">
                                        {{ $playlist->name }}
                                    </button>
                                @empty
                                    <div class="player-playlist-empty" style="padding: 12px 12px; color: #8f95af; font-size: 13px;">Chưa có playlist nào.</div>
                                @endforelse
                            </div>
                            <div style="border-top: 1px solid rgba(255,255,255,0.05); padding: 12px;">
                                <form id="playerCreatePlaylistForm" style="display: flex; gap: 8px; flex-direction: column;">
                                    <input id="playerNewPlaylistName" type="text" placeholder="Tạo playlist mới" autocomplete="off" style="width: 100%; background: #0f1220; border: 1px solid #2b2f44; color: #fff; padding: 10px 12px; border-radius: 10px; outline: none;">
                                    <button type="submit" style="border: none; border-radius: 10px; background: linear-gradient(90deg, #00d1ff 0%, #bd00ff 100%); color: #fff; font-weight: 800; padding: 10px 12px; cursor: pointer;">Tạo playlist mới & thêm bài</button>
                                </form>
                                <a href="{{ route('playlist.index') }}" style="display:block; margin-top: 10px; color: #00d1ff; text-decoration:none; font-weight: 700; text-align: center;">Quản lý playlist</a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
            <div class="player-center">
                <div class="player-controls">
                    <i id="shuffleBtn" class="fas fa-random control-icon"></i>
                    <i id="prevBtn" class="fas fa-step-backward control-icon"></i>
                    <div id="playBtnToggle" class="play-btn-wrapper"><i id="playIcon" class="fas fa-play"></i></div>
                    <i id="nextBtn" class="fas fa-step-forward control-icon"></i>
                    <i id="repeatBtn" class="fas fa-redo control-icon"></i>
                </div>
                <div class="progress-area">
                    <span id="currentTime" style="font-size: 11px; color: var(--text-sub);">0:00</span>
                    <div id="progress" class="progress-container"><div id="progressBar" class="progress-bar-fill"></div></div>
                    <span id="duration" style="font-size: 11px; color: var(--text-sub);">0:00</span>
                </div>
            </div>
            <div class="player-right">
                <i id="volumeIcon" class="fas fa-volume-up" style="color: var(--text-sub);"></i>
                <div class="volume-slider"><div class="volume-fill"></div></div>
            </div>
        </div>
    </footer>
    
<audio id="audioPlayer"></audio>
<script src="{{ asset('js/playerfinal.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Cấu hình CSRF Token cho tất cả request AJAX của Laravel
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    window.isAuthenticated = @json(Auth::check());
    window.songDetailsBaseUrl = @json(url('/song'));
    window.playerPlaylists = @json($playerPlaylistsData ?? []);
    const isAuthenticated = window.isAuthenticated;

    function showLoginPrompt(message) {
        alert(message || 'Vui lòng đăng nhập để sử dụng chức năng này.');
    }

    function setLikeButtonState(button, liked) {
        if (!button) return;

        button.classList.toggle('liked', liked);

        if (button.classList.contains('artist-follow-btn')) {
            button.innerHTML = liked
                ? '<i class="fas fa-check"></i> Đang theo dõi'
                : '<i class="fas fa-plus"></i> Theo dõi';
            return;
        }

        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fas', liked);
            icon.classList.toggle('far', !liked);
        }
    }

    function bindSongLikeButtons() {
        document.querySelectorAll('.btn-like-song').forEach((button) => {
            const songId = button.dataset.id;
            if (!songId) return;

            if (isAuthenticated) {
                $.get('/ajax/like-song/status', { song_id: songId }, function(res) {
                    if (res.status === 'success') {
                        setLikeButtonState(button, !!res.liked);
                    }
                });
            } else {
                setLikeButtonState(button, false);
            }

            button.onclick = function(event) {
                event.preventDefault();

                if (!isAuthenticated) {
                    showLoginPrompt('Vui lòng đăng nhập để thêm bài hát vào danh sách yêu thích.');
                    setLikeButtonState(button, false);
                    return;
                }

                $.post('/ajax/like-song', { song_id: songId }, function(res) {
                    if (res.status === 'success') {
                        setLikeButtonState(button, res.action === 'liked');
                    } else {
                        alert(res.message || 'Không thể cập nhật bài hát yêu thích.');
                    }
                }).fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Vui lòng thử lại sau.');
                    setLikeButtonState(button, false);
                });
            };
        });
    }

    function bindArtistLikeButtons() {
        document.querySelectorAll('.btn-like-artist').forEach((button) => {
            const artistId = button.dataset.id;
            if (!artistId) return;

            button.onclick = function(event) {
                event.preventDefault();

                if (!isAuthenticated) {
                    showLoginPrompt('Vui lòng đăng nhập để thêm nghệ sĩ vào danh sách yêu thích.');
                    setLikeButtonState(button, false);
                    return;
                }

                $.post('/ajax/like-artist', { artist_id: artistId }, function(res) {
                    if (res.status === 'success') {
                        setLikeButtonState(button, !button.classList.contains('liked'));
                    } else {
                        alert(res.message || 'Không thể cập nhật nghệ sĩ yêu thích.');
                    }
                }).fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Vui lòng thử lại sau.');
                    setLikeButtonState(button, false);
                });
            };
        });
    }

    function bindDetailPlayButtons() {
        document.querySelectorAll('.spotify-play-btn').forEach((button) => {
            const songId = button.dataset.id;
            if (!songId) return;

            button.onclick = function(event) {
                event.preventDefault();

                if (typeof window.playSong === 'function') {
                    window.playSong(songId);
                    return;
                }

                window.location.href = `{{ url('/song') }}/${songId}/chitiet`;
            };
        });
    }

    function bindPlayerPlaylistMenu() {
        const toggleButton = document.getElementById('playerPlaylistBtn');
        const popup = document.getElementById('playerPlaylistPopup');
        const playlistList = document.getElementById('playerPlaylistList');
        const createForm = document.getElementById('playerCreatePlaylistForm');
        const newPlaylistName = document.getElementById('playerNewPlaylistName');

        if (!toggleButton || !popup || !playlistList || !createForm || !newPlaylistName) return;

        const renderPlayerPlaylistList = () => {
            const playlists = Array.isArray(window.playerPlaylists) ? window.playerPlaylists : [];
            if (!playlists.length) {
                playlistList.innerHTML = '<div class="player-playlist-empty" style="padding: 12px 12px; color: #8f95af; font-size: 13px;">Chưa có playlist nào.</div>';
                return;
            }

            playlistList.innerHTML = playlists.map((playlist) => `
                <button type="button" class="player-playlist-item" data-playlist-id="${playlist.playlist_id}" style="width: 100%; text-align: left; padding: 12px 12px; background: transparent; border: none; color: #fff; cursor: pointer; display: block; border-radius: 10px;">
                    ${playlist.name}
                </button>
            `).join('');

            playlistList.querySelectorAll('.player-playlist-item').forEach((button) => {
                button.addEventListener('click', () => addCurrentSongToPlaylist(button.dataset.playlistId));
            });
        };

        const getCurrentSong = () => (typeof window.getCurrentSongData === 'function' ? window.getCurrentSongData() : null);

        const addCurrentSongToPlaylist = async (playlistId) => {
            const currentSong = getCurrentSong();
            if (!currentSong || !currentSong.id) {
                alert('Vui lòng phát một bài hát trước.');
                return;
            }

            try {
                const response = await fetch("{{ route('playlist.add-song') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        playlist_id: playlistId,
                        song_id: currentSong.id
                    })
                });

                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    alert(data.message || 'Đã thêm vào playlist.');
                    popup.style.display = 'none';
                } else {
                    alert(data.message || 'Không thể thêm bài hát vào playlist.');
                }
            } catch (error) {
                alert('Lỗi kết nối, vui lòng thử lại.');
            }
        };

        toggleButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
            if (popup.style.display === 'block') {
                renderPlayerPlaylistList();
            }
        });

        createForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const playlistName = newPlaylistName.value.trim();
            if (!playlistName) {
                alert('Vui lòng nhập tên playlist mới.');
                return;
            }

            const currentSong = getCurrentSong();
            if (!currentSong || !currentSong.id) {
                alert('Vui lòng phát một bài hát trước.');
                return;
            }

            try {
                const response = await fetch("{{ route('playlist.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new URLSearchParams({ name: playlistName }).toString()
                });

                const data = await response.json();
                if (!response.ok || data.status !== 'success' || !data.playlist) {
                    alert(data.message || 'Không thể tạo playlist.');
                    return;
                }

                window.playerPlaylists = Array.isArray(window.playerPlaylists) ? window.playerPlaylists : [];
                window.playerPlaylists.unshift(data.playlist);
                newPlaylistName.value = '';
                renderPlayerPlaylistList();

                await addCurrentSongToPlaylist(data.playlist.playlist_id);
            } catch (error) {
                alert('Lỗi kết nối, vui lòng thử lại.');
            }
        });

        document.addEventListener('click', function (event) {
            if (!popup.contains(event.target) && !toggleButton.contains(event.target)) {
                popup.style.display = 'none';
            }
        });
    }

    let searchDebounceTimer = null;
    let searchResultsCache = [];
    let searchOutsideClickBound = false;

    function clearSearchSuggestions() {
        const suggestions = document.getElementById('searchSuggestions');
        if (!suggestions) return;
        suggestions.innerHTML = '';
        suggestions.style.display = 'none';
        searchResultsCache = [];
    }

    function renderSearchSuggestions(results) {
        const suggestions = document.getElementById('searchSuggestions');
        if (!suggestions) return;

        searchResultsCache = Array.isArray(results) ? results : [];

        if (!searchResultsCache.length) {
            suggestions.innerHTML = '<div class="search-suggestion-empty">Không tìm thấy kết quả phù hợp.</div>';
            suggestions.style.display = 'block';
            return;
        }

        suggestions.innerHTML = searchResultsCache.map((item) => `
            <a href="${item.url}" class="search-suggestion-item" data-no-ajax="false">
                <img src="${item.image}" class="search-suggestion-cover" alt="${item.title}">
                <div class="search-suggestion-meta">
                    <div class="search-suggestion-title">${item.title}</div>
                    <div class="search-suggestion-subtitle">${item.subtitle}</div>
                </div>
                <div class="search-suggestion-type">${item.type}</div>
            </a>
        `).join('');

        suggestions.style.display = 'block';
    }

    function bindSearchAutocomplete() {
        const input = document.getElementById('siteSearchInput');
        const form = document.getElementById('siteSearchForm');
        const suggestions = document.getElementById('searchSuggestions');

        if (!input || !form || !suggestions) return;

        input.oninput = function() {
            const query = this.value.trim();

            if (searchDebounceTimer) {
                clearTimeout(searchDebounceTimer);
            }

            if (query.length === 0) {
                clearSearchSuggestions();
                return;
            }

            searchDebounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`, {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        renderSearchSuggestions([]);
                        return;
                    }

                    const data = await response.json();
                    renderSearchSuggestions(data.results || []);
                } catch (error) {
                    renderSearchSuggestions([]);
                }
            }, 180);
        };

        input.onfocus = function() {
            if (searchResultsCache.length) {
                suggestions.style.display = 'block';
            }
        };

        input.onkeydown = function(event) {
            if (event.key !== 'Enter') return;

            event.preventDefault();
            const firstResult = searchResultsCache[0];

            if (firstResult && firstResult.url) {
                clearSearchSuggestions();
                if (typeof window.partialNavigate === 'function') {
                    window.partialNavigate(firstResult.url);
                } else {
                    window.location.href = firstResult.url;
                }
            }
        };

        if (!searchOutsideClickBound) {
            document.addEventListener('click', function(event) {
                const currentForm = document.getElementById('siteSearchForm');
                if (!currentForm || currentForm.contains(event.target)) {
                    return;
                }

                clearSearchSuggestions();
            });
            searchOutsideClickBound = true;
        }

        suggestions.onclick = function(event) {
            const link = event.target.closest('a[href]');
            if (!link) return;

            clearSearchSuggestions();
            if (typeof window.partialNavigate === 'function') {
                event.preventDefault();
                window.partialNavigate(link.href);
            }
        };
    }

    // 1. CHỨC NĂNG THẢ TIM
    bindSongLikeButtons();
    bindArtistLikeButtons();
    bindDetailPlayButtons();
    bindPlayerPlaylistMenu();
    bindSearchAutocomplete();

    // ==========================================
    // 2. LOGIC TRÌNH PHÁT NHẠC (AUDIO PLAYER)
    // ==========================================
    const trackingAudio = document.getElementById('audioPlayer');
    let currentSongId = null;
    let hasRecordedHistory = false;

    // Hàm gọi khi chuyển bài hát mới
    function loadNewSong(songId) {
        currentSongId = songId;
        hasRecordedHistory = false; // Reset cờ trạng thái
    }

    if(trackingAudio) {
        // Sự kiện: Khi đang phát nhạc (Tự động lưu lịch sử sau 30 giây)
        trackingAudio.addEventListener('timeupdate', function() {
            if (!isAuthenticated) {
                return;
            }

            // Nếu nghe được hơn 30 giây và chưa lưu lịch sử cho bài này
            if (trackingAudio.currentTime > 30 && !hasRecordedHistory && currentSongId) {
                $.post('/ajax/record-history', { song_id: currentSongId });
                hasRecordedHistory = true; // Đánh dấu đã lưu để không bị gửi liên tục
            }
        });

        // Sự kiện: Khi bài hát kết thúc (Cộng 1 lượt nghe)
        trackingAudio.addEventListener('ended', function() {
            if (!isAuthenticated) {
                return;
            }

            if (currentSongId) {
                $.post('/ajax/increment-view', { song_id: currentSongId }, function(res) {
                    if(res.status === 'success') {
                        console.log('Đã cộng lượt nghe: ' + res.views);
                        // Cập nhật lại số lượt nghe trên giao diện nếu cần
                    }
                });
            }
        });
    }

    // ==========================================
    // 3. PARTIAL NAVIGATION (GIỮ NGUYÊN LAYOUT)
    // ==========================================
    const contentSelector = '.content-area';
    let navAbortController = null;

    function runScriptsIn(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach((oldScript) => {
            const newScript = document.createElement('script');

            if (oldScript.src) {
                if (document.querySelector(`script[src="${oldScript.src}"]`)) {
                    return;
                }
                newScript.src = oldScript.src;
            } else {
                newScript.textContent = oldScript.textContent;
            }

            document.body.appendChild(newScript);
            if (!newScript.src) {
                document.body.removeChild(newScript);
            }
        });
    }

    function refreshPlayerBindings() {
        if (typeof window.buildSongList === 'function') {
            window.buildSongList();
        }
        bindSongLikeButtons();
        bindArtistLikeButtons();
        bindDetailPlayButtons();
        bindSearchAutocomplete();
    }

    async function partialNavigate(url, options = {}) {
        const target = document.querySelector(contentSelector);
        if (!target) {
            window.location.href = url;
            return;
        }

        if (typeof window.persistPlayerState === 'function') {
            window.persistPlayerState();
        }

        if (navAbortController) {
            navAbortController.abort();
        }
        navAbortController = new AbortController();

        try {
            target.style.opacity = '0.65';

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: navAbortController.signal
            });

            if (!response.ok) {
                window.location.href = url;
                return;
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const nextContent = doc.querySelector(contentSelector);
            if (!nextContent) {
                window.location.href = url;
                return;
            }

            const currentHeader = document.querySelector('.header');
            const nextHeader = doc.querySelector('.header');
            if (currentHeader && nextHeader) {
                currentHeader.innerHTML = nextHeader.innerHTML;
            }

            const currentSidebar = document.querySelector('.sidebar-left');
            const nextSidebar = doc.querySelector('.sidebar-left');
            if (currentSidebar && nextSidebar) {
                currentSidebar.innerHTML = nextSidebar.innerHTML;
            }

            target.innerHTML = nextContent.innerHTML;
            target.scrollTop = 0;
            document.title = doc.title || document.title;

            runScriptsIn(target);
            refreshPlayerBindings();

            if (!options.fromPopState) {
                history.pushState({ partial: true }, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.href = url;
            }
        } finally {
            target.style.opacity = '1';
        }
    }

    window.partialNavigate = partialNavigate;

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.target === '_blank' || link.hasAttribute('download') || event.metaKey || event.ctrlKey || event.shiftKey) return;
        if (link.dataset.noAjax === 'true') return;

        const nextUrl = new URL(link.href, window.location.origin);
        const isSameOrigin = nextUrl.origin === window.location.origin;
        const isAdminArea = nextUrl.pathname.startsWith('/admin');

        if (!isSameOrigin || isAdminArea) return;

        event.preventDefault();
        partialNavigate(nextUrl.href);
    });

    window.addEventListener('beforeunload', () => {
        if (typeof window.persistPlayerState === 'function') {
            window.persistPlayerState();
        }
    });

    window.addEventListener('popstate', () => {
        partialNavigate(window.location.href, { fromPopState: true });
    });
</script>
</body>
</html>