<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina Audio - SOUNDWAVE</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon2.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
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
        
        body { 
            background-color: var(--bg-dark); color: var(--text-main); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            height: 100vh; overflow: hidden; display: flex; flex-direction: column; 
        }

        .header { 
            height: 70px; display: flex; justify-content: space-between; align-items: center; 
            padding: 0 30px; background: var(--bg-panel); border-bottom: 1px solid var(--border-color); flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; gap: 30px; }
        .logo-neon { color: #00e5ff; font-weight: bold; font-size: 20px; text-shadow: 0 0 10px rgba(0,229,255,0.8); display: flex; align-items: center; gap: 10px;}
        .main-nav a { color: var(--text-sub); text-decoration: none; margin-right: 20px; font-weight: 500; font-size: 14px; transition: 0.3s; }
        .main-nav a.active, .main-nav a:hover { color: #00d1ff; border-bottom: 2px solid #00d1ff; padding-bottom: 5px; }
        
        .search-bar { background: rgba(255, 255, 255, 0.1); padding: 8px 15px; border-radius: 20px; width: 350px; display: flex; align-items: center; }
        .search-bar input { border: none; background: transparent; color: #fff; margin-left: 10px; width: 100%; outline: none; }

        .btn-action { padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 500; font-size: 13px; cursor: pointer; }
        .btn-style-gradient { background: var(--primary-gradient); color: #fff !important; border: none; }
        .btn-style-outline { background: transparent; border: 1.5px solid #ffffff; color: #ffffff !important; }
        .guest-actions { display: flex; gap: 15px; }
        
        .user-profile { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.1); padding: 5px 15px 5px 5px; border-radius: 50px; cursor: pointer; }
        .user-profile img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .user-profile span { font-size: 13px; font-weight: 600; }

        .main-container { flex: 1; display: flex; overflow: hidden; padding-bottom: 80px; }
        .sidebar-left { width: 240px; background: var(--bg-panel); border-right: 1px solid var(--border-color); padding: 20px; flex-shrink: 0; overflow-y: auto; }
        .menu-group h3 { color: var(--text-sub); font-size: 12px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;}
        .menu-item { display: block; padding: 12px 15px; color: var(--text-sub); text-decoration: none; margin-bottom: 5px; border-radius: 8px; font-size: 14px; transition: 0.3s; }
        .menu-item.active, .menu-item:hover { background: rgba(0, 209, 255, 0.1); color: #00d1ff; font-weight: bold; border-left: 3px solid #00d1ff; }

        .content-area { flex: 1; overflow-y: auto; padding: 30px 40px; background: radial-gradient(circle at 10% 10%, #1a1c29 0%, #0d0f17 90%); }

        .sidebar-right { width: 300px; background: var(--bg-panel); border-left: 1px solid var(--border-color); padding: 20px; flex-shrink: 0; overflow-y: auto; text-align: center; }
        .song-cover { width: 100%; aspect-ratio: 1/1; border-radius: 12px; object-fit: cover; margin-bottom: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        #lyricsBox { color: var(--text-sub); font-size: 14px; line-height: 2; text-align: left; margin-top: 20px; padding-bottom: 20px;}
        .lyrics-line { display: block; margin-bottom: 10px; transition: 0.3s; }
        .lyrics-line.active { color: #00d1ff; font-size: 16px; font-weight: bold; }

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

        .genre-nav { display: flex; gap: 20px; padding: 12px 30px; background: var(--bg-dark); border-bottom: 1px solid var(--border-color); overflow-x: auto; flex-shrink: 0; justify-content: center; }
        .genre-nav::-webkit-scrollbar { display: none; }
        .genre-card { display: flex; flex-direction: row; align-items: center; gap: 10px; padding: 10px 20px; background: #1a1c26; border-radius: 10px; border: 1px solid rgba(255,255,255,0.05); cursor: pointer; transition: 0.3s; height: 45px; }
        .genre-card:hover { background: var(--primary-gradient); border-color: transparent; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(189,0,255,0.3); }
        .genre-icon { font-size: 16px; color: var(--text-sub); display: flex; align-items: center; transition: 0.3s; }
        .genre-name { font-size: 13px; font-weight: 600; color: var(--text-sub); white-space: nowrap; transition: 0.3s; }
        .genre-card:hover .genre-icon, .genre-card:hover .genre-name { color: #fff; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: transparent; border-radius: 10px; transition: background 0.3s ease; }
        *:hover::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 209, 255, 0.5) !important; }

        .user-profile-trigger:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .dropdown-menu-custom a:hover {
            background: rgba(0, 209, 255, 0.1);
        }
        .dropdown-menu-custom button:hover {
            background: rgba(255, 68, 102, 0.1);
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <div class="logo-neon"><span class="music-icon">🎵</span> SOUNDWAVE</div>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a>
                <a href="#">Albums</a>
                <a href="#">Bảng xếp hạng</a>
                <a href="#">Phát hành mới</a>
                <a href="#">Nghệ sĩ</a>
            </nav>
        </div>

        <div class="search-bar">
            <span>🔍</span>
            <input type="text" placeholder="Tìm kiếm bài hát, nghệ sĩ, album...">
        </div>

        <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
            @auth
                <div class="user-profile" style="position: relative;">
                    <div class="user-profile-trigger" onclick="toggleMenu()" style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 5px 10px; border-radius: 50px; transition: 0.3s;">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ asset(auth()->user()->avatar_url) }}?t={{ time() }}" id="headerAvatar" alt="User" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div id="headerAvatar" style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #00d1ff, #8A2BE2); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;">
                                {{ substr(auth()->user()->full_name ?? auth()->user()->username, 0, 1) }}
                            </div>
                        @endif
                        <span>{{ auth()->user()->full_name ?? auth()->user()->username }}</span>
                        <i class="fas fa-caret-down dropdown-icon" style="color: var(--text-sub); margin-left: 5px; transition: transform 0.3s;"></i>
                    </div>

                    <div class="dropdown-menu-custom" id="dropdownMenu" style="position: absolute; top: 100%; right: 0; margin-top: 10px; background: #1a1c26; border-radius: 12px; min-width: 200px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 1000; display: none; overflow: hidden;">
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #fff; text-decoration: none; transition: 0.3s; border-bottom: 1px solid #2d2f3b;">
                            <i class="fas fa-user-circle" style="width: 20px; color: #00d1ff;"></i>
                            <span>Hồ sơ cá nhân</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; background: none; border: none; color: #ff4466; cursor: pointer; width: 100%; text-align: left; font-size: 14px; transition: 0.3s;">
                                <i class="fas fa-sign-out-alt" style="width: 20px;"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </div>
                <i class="fas fa-bell" style="color: #ffb700; cursor: pointer;"></i>
            @else
                <div class="guest-actions">
                    <a href="{{ route('register') }}" class="btn-action btn-style-outline">Đăng ký</a>
                    <a href="{{ route('login') }}" class="btn-action btn-style-gradient">Đăng nhập</a>
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
                    <a href="#" class="menu-item active">Playlist</a>
                    <a href="#" class="menu-item">Favorite Songs</a>
                    <a href="#" class="menu-item">Favorites Artists</a>
                    <a href="#" class="menu-item">Listening History</a>
                </div>
                <div class="menu-group" style="margin-top: 30px;">
                    <h3>DANH SÁCH CHỜ</h3>
                    <div style="color: #666; font-size: 13px; padding-left: 15px;">Chưa có bài hát nào...</div>
                </div>
            </aside>

        <aside class="sidebar-right">
            <h3 style="color: #fff; font-size: 16px; margin-bottom: 20px; text-transform: uppercase;">Lời Bài Hát</h3>
            <img id="rightCover" class="song-cover" src="{{ asset('image/default-cover.jpg') }}">
            <h4 id="rightTitle" style="color: #fff; margin-bottom: 5px;">Chưa chọn bài hát</h4>
            <p id="rightArtist" style="color: var(--text-sub); font-size: 13px;">--</p>
            
            <div id="lyricsBox">
                <span class="lyrics-line">Hãy phát một bài hát</span>
                <span class="lyrics-line">xem lời tại đây nhé!</span>
            </div>
        </aside>

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
                    <img src="{{ asset('image/default-cover.jpg') }}" class="player-song-img">
                    <div>
                        <div style="color: #fff; font-size: 14px; font-weight: bold;">Tên bài hát</div>
                        <div style="color: var(--text-sub); font-size: 12px;">Tên nghệ sĩ</div>
                    </div>
                    <i class="far fa-heart" style="color: var(--text-sub); margin-left: 20px; cursor: pointer;"></i>
                </div>
                <div class="player-center">
                    <div class="player-controls">
                        <i class="fas fa-random control-icon"></i>
                        <i class="fas fa-step-backward control-icon"></i>
                        <div class="play-btn-wrapper"><i class="fas fa-play"></i></div>
                        <i class="fas fa-step-forward control-icon"></i>
                        <i class="fas fa-redo control-icon"></i>
                    </div>
                    <div class="progress-area">
                        <span style="font-size: 11px; color: var(--text-sub);">0:00</span>
                        <div class="progress-container"><div class="progress-bar-fill"></div></div>
                        <span style="font-size: 11px; color: var(--text-sub);">3:45</span>
                    </div>
                </div>
                <div class="player-right">
                    <i class="fas fa-volume-up" style="color: var(--text-sub);"></i>
                    <div class="volume-slider"><div class="volume-fill"></div></div>
                </div>
            </div>
        </footer>
    

    <script>
        function toggleMenu() {
            var menu = document.getElementById('dropdownMenu');
            var icon = document.querySelector('.dropdown-icon');
            
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                menu.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }
        
        document.addEventListener('click', function(event) {
            var profile = document.querySelector('.user-profile');
            var menu = document.getElementById('dropdownMenu');
            var icon = document.querySelector('.dropdown-icon');
            
            if (profile && !profile.contains(event.target)) {
                if (menu) menu.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
        
        window.updateHeaderAvatar = function(imageUrl) {
            var headerAvatar = document.getElementById('headerAvatar');
            if (!headerAvatar) return;
            
            if (imageUrl && imageUrl !== 'null' && imageUrl !== '') {
                var finalUrl = imageUrl;
                if (!imageUrl.startsWith('http') && !imageUrl.startsWith('/storage')) {
                    finalUrl = '/storage/' + imageUrl;
                }
                
                if (headerAvatar.tagName === 'IMG') {
                    headerAvatar.src = finalUrl + '?t=' + Date.now();
                } else {
                    var newImg = document.createElement('img');
                    newImg.src = finalUrl + '?t=' + Date.now();
                    newImg.id = 'headerAvatar';
                    newImg.alt = 'User';
                    newImg.style.width = '35px';
                    newImg.style.height = '35px';
                    newImg.style.borderRadius = '50%';
                    newImg.style.objectFit = 'cover';
                    headerAvatar.parentNode.replaceChild(newImg, headerAvatar);
                }
            } else {
                var spanElement = document.querySelector('.user-profile-trigger span');
                var firstChar = spanElement ? spanElement.innerText.charAt(0) : 'U';
                var newDiv = document.createElement('div');
                newDiv.id = 'headerAvatar';
                newDiv.style.width = '35px';
                newDiv.style.height = '35px';
                newDiv.style.borderRadius = '50%';
                newDiv.style.background = 'linear-gradient(135deg, #00d1ff, #8A2BE2)';
                newDiv.style.display = 'flex';
                newDiv.style.alignItems = 'center';
                newDiv.style.justifyContent = 'center';
                newDiv.style.color = '#fff';
                newDiv.style.fontWeight = 'bold';
                newDiv.innerText = firstChar;
                headerAvatar.parentNode.replaceChild(newDiv, headerAvatar);
            }
        };
    </script>

</body>
</html>