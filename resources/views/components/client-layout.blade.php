@props(['title' => 'Lumina Audio - SOUNDWAVE'])
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon.png') }}">
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
        .logo-icon { width: 22px; height: 22px; object-fit: contain; }
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

        .app-toast-stack {
            position: fixed;
            left: 20px;
            bottom: 96px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 2000;
            pointer-events: none;
        }

        .app-toast {
            min-width: 260px;
            max-width: 360px;
            border-radius: 12px;
            padding: 11px 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(12, 16, 26, 0.94);
            color: #e8f4ff;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.38);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            line-height: 1.4;
            transform: translateY(8px);
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .app-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .app-toast.success {
            border-color: rgba(0, 209, 255, 0.5);
        }

        .app-toast.error {
            border-color: rgba(255, 88, 114, 0.55);
        }

        .app-toast.warning {
            border-color: rgba(255, 190, 92, 0.55);
        }

        .app-toast-icon {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .song-play-btn {
            position: relative;
            overflow: hidden;
        }

        .song-play-btn.is-playing {
            color: #06131a;
        }

        .song-play-wave {
            display: inline-flex;
            align-items: flex-end;
            gap: 2px;
            width: 14px;
            height: 12px;
        }

        .song-play-wave span {
            width: 2px;
            border-radius: 999px;
            background: currentColor;
            animation: songPlayWave 0.9s ease-in-out infinite;
            transform-origin: bottom center;
            opacity: 0.95;
        }

        .song-play-wave span:nth-child(1) { height: 5px; animation-delay: 0s; }
        .song-play-wave span:nth-child(2) { height: 10px; animation-delay: 0.12s; }
        .song-play-wave span:nth-child(3) { height: 7px; animation-delay: 0.24s; }

        @keyframes songPlayWave {
            0%, 100% { transform: scaleY(0.55); opacity: 0.55; }
            50% { transform: scaleY(1.15); opacity: 1; }
        }

        /* NÚT BẤM & USER CONTROL */
        .btn-action { padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 500; font-size: 13px; cursor: pointer; }
        .btn-style-gradient { background: var(--primary-gradient); color: #fff !important; border: none; }
        .btn-style-outline { background: transparent; border: 1.5px solid #ffffff; color: #ffffff !important; }
        .guest-actions { display: flex; gap: 15px; }
        
        .user-profile { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.1); padding: 5px 15px 5px 5px; border-radius: 50px; cursor: pointer; }
        .user-profile img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .user-profile span { font-size: 13px; font-weight: 600; }

        /* NOTIFICATION DROPDOWN */
        .notification-wrapper {
            position: relative;
            display: inline-block;
            z-index: 200;
        }

        .noti-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 340px;
            background: rgba(26, 28, 38, 0.95) !important;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 2000 !important;
            overflow: hidden;
            visibility: hidden;
            opacity: 0;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .noti-dropdown.show {
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto;
        }

        .noti-header {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .noti-header h3 { font-size: 16px; margin: 0; color: #fff; }

        .noti-content {
            max-height: 350px;
            overflow-y: auto;
        }

        .noti-item {
            padding: 12px 15px;
            display: flex;
            gap: 12px;
            transition: 0.3s;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .noti-item:hover { background: rgba(255,255,255,0.05); }
        .noti-item.unread { background: rgba(0, 209, 255, 0.08); }

        .noti-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }

        .noti-icon-box {
            width: 45px; height: 45px; border-radius: 6px;
            display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
        }
        .noti-icon-box.info { background: rgba(0, 209, 255, 0.2); color: #00d1ff; }
        .noti-icon-box.success { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .noti-icon-box.warning { background: rgba(255, 180, 0, 0.2); color: #ffb400; }
        .noti-icon-box.error { background: rgba(255, 68, 102, 0.2); color: #ff4466; }

        .noti-text { flex: 1; }
        .noti-time { font-size: 11px; color: rgba(255,255,255,0.5); display: block; margin-top: 6px; }

        /* Notification Modal Styles */
        #notiModal {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #notiModal > div {
            animation: slideUp 0.3s ease;
        }

        #notiModalContent {
            animation: slideUp 0.3s ease;
        }

        #notiModalTitle {
            font-weight: 600;
            word-break: break-word;
        }

        #notiModalBody {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        #notiModalImage img {
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .noti-text p { font-size: 13px; margin: 0 0 4px 0; color: #ddd; line-height: 1.4; }
        .noti-text b { color: #fff; }
        .noti-time { font-size: 11px; color: rgba(255,255,255,0.5); }

        .noti-footer { padding: 10px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); }
        .noti-footer a { color: #00d1ff; font-size: 13px; text-decoration: none; }

        /* 3. BỐ CỤC 3 CỘT (Cực kỳ quan trọng) */
        .main-container { 
            flex: 1; display: flex; overflow: hidden; padding-bottom: 80px; /* Chừa chỗ cho Player */
        }
        
        /* Cột Trái (Menu) */
        .sidebar-left { 
            width: 240px; background: var(--bg-panel); border-right: 1px solid var(--border-color); 
            padding: 20px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .menu-group {
            flex-shrink: 0;
        }

        .menu-group.queue-group {
            margin-top: 30px;
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
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
        .sidebar-artist-meta { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 2px; }
        .sidebar-artist-text { color: var(--text-sub); font-size: 13px; margin: 0; }
        .sidebar-follow-artist-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            width: 30px;
            height: 30px;
            padding: 0;
            color: #e6e6e6;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        }
        .sidebar-follow-artist-btn:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.14);
            transform: scale(1.02);
        }
        .sidebar-follow-artist-btn.liked {
            color: #ff007a;
            border-color: rgba(255, 0, 122, 0.7);
            background: rgba(255, 0, 122, 0.16);
        }
        #lyricsBox { color: var(--text-sub); font-size: 14px; line-height: 2; text-align: left; margin-top: 20px; padding-bottom: 20px;}
        .lyrics-line { display: block; margin-bottom: 10px; font-size: 14px; font-weight: 600; line-height: 1.9; color: rgba(255, 255, 255, 0.58); transform-origin: left center; will-change: transform; transition: color .18s ease, transform .18s ease, text-shadow .18s ease, background .18s ease; }
        .lyrics-line[data-lyric-time] { cursor: pointer; border-radius: 6px; padding: 1px 4px; margin-left: -4px; margin-right: -4px; }
        .lyrics-line[data-lyric-time]:hover { background: rgba(255,255,255,0.1); color: rgba(255, 255, 255, 0.9); }
        .lyrics-line.active { color: #00d1ff; transform: scale(1.035); text-shadow: 0 0 8px rgba(0, 209, 255, 0.25); }
        .sidebar-panel-tabs { display: flex; gap: 8px; margin-top: 14px; margin-bottom: 10px; }
        .sidebar-panel-tab { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.18); border-radius: 10px; background: rgba(255,255,255,0.05); color: #cfd5ea; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: background .22s ease, color .22s ease, border-color .22s ease, transform .22s ease, box-shadow .22s ease; }
        .sidebar-panel-tab i { transition: transform .25s ease, opacity .25s ease; }
        .sidebar-panel-tab:hover { background: rgba(255,255,255,0.12); color: #fff; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(0,0,0,0.28); }
        .sidebar-panel-tab:active { transform: scale(0.96); }
        .sidebar-panel-tab.is-active { background: rgba(0, 209, 255, 0.2); border-color: rgba(0, 209, 255, 0.55); color: #00d1ff; box-shadow: 0 0 0 1px rgba(0, 209, 255, 0.18) inset, 0 10px 24px rgba(0, 209, 255, 0.14); }
        .sidebar-panel-tab.is-active i { transform: scale(1.08); }
        #lyricsBox, #sidebarInfoPanel { transition: opacity .26s cubic-bezier(0.2, 0.8, 0.2, 1), transform .26s cubic-bezier(0.2, 0.8, 0.2, 1); }
        #sidebarInfoPanel { display: none; text-align: left; margin-top: 8px; }
        .sidebar-section-title { color: #8f95af; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .7px; margin: 14px 0 8px; }
        .sidebar-info-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09); border-radius: 10px; padding: 10px 12px; }
        .sidebar-info-row { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 7px; }
        .sidebar-info-row:last-child { margin-bottom: 0; }
        .sidebar-info-label { color: #8f95af; font-size: 12px; }
        .sidebar-info-value { color: #fff; font-size: 12px; font-weight: 700; text-align: right; }
        .sidebar-related-list { display: grid; gap: 8px; }
        .sidebar-related-item { width: 100%; border: none; background: rgba(255,255,255,0.05); border-radius: 10px; padding: 8px; display: flex; align-items: center; gap: 8px; cursor: pointer; text-align: left; }
        .sidebar-related-item:hover { background: rgba(255,255,255,0.12); }
        .sidebar-related-thumb { width: 36px; height: 36px; border-radius: 7px; object-fit: cover; flex-shrink: 0; }
        .sidebar-related-title { color: #fff; font-size: 12px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-related-artist { color: #9da3bd; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* 4. PLAYER THANH NHẠC DƯỚI ĐÁY */
        footer { height: 80px; background: #1a1c26; border-top: 1px solid #2d2f3b; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; }
        .player-bar { height: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        
        .player-left { width: 25%; display: flex; align-items: center; }
        .player-song-img { width: 50px; height: 50px; border-radius: 8px; margin-right: 15px; object-fit: cover; }
        
        .player-center { flex: 1; display: flex; flex-direction: column; align-items: center; max-width: 600px; }
        .player-controls { display: flex; align-items: center; gap: 25px; margin-bottom: 8px; }
        .control-icon { color: var(--text-sub); cursor: pointer; font-size: 16px; transition: color 0.2s ease, text-shadow 0.2s ease; }
        .control-icon:hover { color: #fff; }
        .control-icon.active { color: #fff; text-shadow: 0 0 10px rgba(255, 255, 255, 0.35); }
        .play-btn-wrapper { width: 42px; height: 42px; background: var(--primary-gradient); border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #000; cursor: pointer; }
        
        .progress-area { display: flex; align-items: center; width: 100%; gap: 12px; }
        .progress-container { flex: 1; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; cursor: pointer; position: relative; }
        .progress-bar-fill { height: 100%; background: var(--primary-gradient); border-radius: 10px; width: 30%; }
        
        .player-right { width: 25%; display: flex; justify-content: flex-end; align-items: center; gap: 10px; }
        .volume-slider { width: 100px; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .volume-fill { height: 100%; background: var(--primary-gradient); border-radius: 10px; width: 70%; }

        /* Nút play trong các danh sách bài hát */
        .song-play-btn {
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .song-play-btn:hover {
            transform: translateY(-1px) scale(1.06);
            filter: brightness(1.12);
            box-shadow: 0 8px 18px rgba(0, 209, 255, 0.35);
        }

        .song-play-btn:active {
            transform: scale(0.97);
            filter: brightness(0.98);
        }

        .song-add-playlist-btn {
            width: 22px;
            min-width: 22px;
            height: 22px;
            border: none;
            background: transparent;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            margin-right: 0;
            padding: 0;
            box-shadow: none;
            transition: transform 0.18s ease, opacity 0.18s ease, color 0.18s ease, text-shadow 0.18s ease;
            line-height: 1;
        }

        .song-add-playlist-btn:hover {
            transform: translateY(-1px) scale(1.08);
            opacity: 1;
            color: #00d1ff;
            text-shadow: 0 0 10px rgba(0, 209, 255, 0.55);
        }

        .song-add-playlist-btn:active {
            transform: scale(0.97);
            opacity: 0.82;
        }

        .song-duration-wrap {
            display: block;
            position: relative;
            min-height: 22px;
            gap: 0;
            width: 100%;
            white-space: nowrap;
        }

        .song-duration-text {
            display: block;
            width: 100%;
            min-width: 42px;
            text-align: right;
            padding-right: 34px;
            box-sizing: border-box;
        }

        .song-playlist-action-slot {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            width: 120px;
            min-width: 120px;
            margin-left: auto;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .song-item-row:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .song-item-row:hover .song-add-playlist-btn {
            color: #00d1ff;
        }

        .song-item-row.active-playing {
            background: rgba(0, 209, 255, 0.09);
            box-shadow: inset 0 0 0 1px rgba(0, 209, 255, 0.18);
        }

        .player-playlist-item:hover {
            background: rgba(0, 209, 255, 0.12) !important;
            color: #00d1ff !important;
        }

        /* Context Menu */
        .song-context-menu {
            position: fixed;
            background: #2a2d38;
            border: 1px solid #3d4050;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            z-index: 10000;
            min-width: 200px;
            overflow: hidden;
        }

        .song-context-menu-item {
            display: block;
            width: 100%;
            padding: 10px 16px;
            background: transparent;
            border: none;
            color: #e5e7eb;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.15s ease;
        }

        .song-context-menu-item:hover {
            background: rgba(0, 209, 255, 0.15);
            color: #00d1ff;
            padding-left: 20px;
        }

        .song-context-menu-item:active {
            background: rgba(0, 209, 255, 0.25);
        }

        .song-context-menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 4px 0;
        }

        /* 5. THANH THỂ LOẠI (GENRE NAV) - BẢN NHỎ GỌN */
        .genre-nav {
            display: flex; gap: 20px; padding: 12px 30px; 
            background: var(--bg-dark); border-bottom: 1px solid var(--border-color);
            overflow-x: auto; flex-shrink: 0;
            justify-content: flex-start;
            flex-wrap: nowrap;
            scroll-behavior: smooth;
            scrollbar-width: none;
            scrollbar-color: transparent transparent;
        }

        .genre-nav::-webkit-scrollbar {
            height: 8px;
        }

        .genre-nav::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 999px;
        }

        .genre-nav::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 999px;
        }

        .genre-nav:hover {
            scrollbar-width: thin;
            scrollbar-color: rgba(47, 172, 255, 0.85) rgba(255, 255, 255, 0.08);
        }

        .genre-nav:hover::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
        }

        .genre-nav:hover::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, rgba(47, 172, 255, 0.9), rgba(176, 74, 255, 0.9));
        }

        .genre-nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(90deg, rgba(47, 172, 255, 1), rgba(176, 74, 255, 1));
        }

        .genre-nav > a {
            flex-shrink: 0;
        }
        
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
            white-space: nowrap;
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

        /* Danh sách chờ */
        #queue-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .queue-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            background: rgba(255,255,255,0.03);
            transition: 0.2s;
            cursor: pointer;
        }
        .queue-item:hover {
            background: rgba(255,255,255,0.08);
        }
        .queue-item-img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }
        .queue-item-info {
            flex: 1;
            overflow: hidden;
        }
        .queue-item-title {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .queue-item-artist {
            font-size: 11px;
            color: var(--text-sub);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .queue-item-remove {
            color: var(--text-sub);
            cursor: pointer;
            padding: 5px;
            border-radius: 20px;
            transition: 0.2s;
        }
        .queue-item-remove:hover {
            color: #ff007a;
            background: rgba(255,0,122,0.1);
        }

        #queue-list-container {
            flex: 1;
            min-height: 0;
            max-height: none;
            overflow-y: hidden;
            overflow-x: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            padding-right: 4px;
            padding-bottom: 0;
        }

        #queue-list-container.has-items {
            overflow-y: auto;
            padding-bottom: 8px;
            scroll-padding-bottom: 8px;
        }

        #queue-list {
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <div class="logo-neon"><img class="logo-icon" src="{{ asset('image/icon.png') }}" alt="Soundwave Icon"> SOUNDWAVE</div>
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

        <div class="header-right" style="display: flex; align-items: center; gap: 20px; position: relative; z-index: 100;">
            @auth
                <details class="user-profile" style="position: relative;">
                    <summary style="list-style: none; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <img src="{{ auth()->user()->avatar_url ? asset(auth()->user()->avatar_url) : asset('image/default_user.png') }}" alt="User">
                        <span>{{ auth()->user()->username ?? 'Người dùng' }}</span>
                        <i class="fas fa-caret-down" style="color: var(--text-sub);"></i>
                    </summary>

                    <div style="position: absolute; top: calc(100% + 10px); right: 0; min-width: 220px; background: #171a25; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; box-shadow: 0 18px 40px rgba(0,0,0,0.45); z-index: 120;">
                        @if((int) (auth()->user()->is_admin ?? 0) === 1)
                            <a href="{{ route('admin.dashboard') }}" data-no-ajax="true" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #fff; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <i class="fas fa-shield-alt" style="color: #00d1ff;"></i>
                                <span>Khu vực Admin</span>
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" data-no-ajax="false" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #fff; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <i class="fas fa-user-circle" style="color: #00d1ff;"></i>
                            <span>Hồ sơ cá nhân</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="width: 100%; border: none; background: transparent; color: #ff5f7b; text-align: left; padding: 12px 16px; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </details>
                <div class="notification-wrapper" style="position: relative; display: inline-block; z-index: 200;">
                    <button id="notiToggleBtn" type="button" style="background: none; border: none; color: #ffb700; cursor: pointer; font-size: 20px; position: relative; padding: 8px;">
                        <i class="fas fa-bell"></i>
                        <span id="notiRedDot" class="notification-red-dot" style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: #ff4466; border-radius: 50%; display: none;"></span>
                    </button>
                    <div class="noti-dropdown" id="notiDropdown">
                        <div class="noti-header">
                            <h3 style="margin: 0;">Thông báo</h3>
                            <button type="button" id="notiMarkAllRead" style="background: none; border: none; color: #00d1ff; cursor: pointer; font-size: 12px; padding: 0;"><i class="fas fa-check"></i> Đánh dấu tất cả</button>
                        </div>
                        <div class="noti-content" id="notiContent" style="max-height: 350px; overflow-y: auto;">
                            <p style="text-align: center; color: #888; padding: 20px;">Đang tải...</p>
                        </div>
                    </div>
                </div>

                <!-- Notification Detail Modal -->
                <div id="notiModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9000; align-items: center; justify-content: center;">
                    <div style="background: #1a1c26; border-radius: 16px; width: 90%; max-width: 500px; max-height: 80vh; overflow-y: auto; border: 1px solid #2d2f3b; position: relative;">
                        <button id="notiModalClose" type="button" style="position: absolute; top: 15px; right: 15px; background: none; border: none; color: #888; cursor: pointer; font-size: 24px; z-index: 9001;">&times;</button>
                        
                        <div id="notiModalContent" style="padding: 30px;">
                            <div id="notiModalImage" style="margin-bottom: 20px; display: none;">
                                <img id="notiModalImg" src="" alt="" style="width: 100%; border-radius: 12px; object-fit: cover;">
                            </div>
                            <h2 id="notiModalTitle" style="color: #fff; margin: 0 0 15px 0; font-size: 24px;"></h2>
                            <p id="notiModalMeta" style="color: #888; font-size: 12px; margin: 0 0 20px 0;"></p>
                            <div id="notiModalBody" style="color: #ddd; line-height: 1.6; font-size: 14px;"></div>
                        </div>
                    </div>
                </div>
                        
                    </div>
                </div>
            @else
                <div class="guest-actions">
                    <a href="{{ route('login') }}" class="btn-action btn-style-gradient" data-no-ajax="true">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn-action btn-style-outline" data-no-ajax="true">Đăng ký</a>
                </div>
            @endauth
        </div>
    </header>

    <nav class="genre-nav">
        @php
            $genreList = \App\Models\Genre::query()->orderBy('name')->get();
            $genreIcons = [
                'pop' => 'fas fa-microphone-alt',
                'ballad' => 'fas fa-guitar',
                'rock' => 'fas fa-bolt',
                'hip hop' => 'fas fa-compact-disc',
                'r&b' => 'fas fa-heart',
                'edm' => 'fas fa-headphones',
                'acoustic' => 'fas fa-leaf',
                'indie' => 'fas fa-feather-alt',
                'jazz' => 'fas fa-wine-glass-alt',
                'classical' => 'fas fa-music',
                'k pop' => 'fas fa-crown',
                'v pop' => 'fas fa-star',
            ];
        @endphp

        @foreach($genreList as $genre)
        @php
            $normalizedName = strtolower(trim((string) $genre->name));
            $iconClass = $genreIcons[$normalizedName] ?? 'fas fa-music';
        @endphp
        <a href="{{ route('genres.show', ['id' => $genre->genre_id]) }}" style="text-decoration: none;" class="{{ request()->routeIs('genres.show') && (int) request()->route('id') === (int) $genre->genre_id ? 'active' : '' }}">
            <div class="genre-card">
                <div class="genre-icon"><i class="{{ $iconClass }}"></i></div>
                <div class="genre-name">{{ $genre->name }}</div>
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
                    <a href="{{ route('login') }}" class="menu-item" data-no-ajax="true">Playlist</a>
                    <a href="{{ route('login') }}" class="menu-item" data-no-ajax="true">Favorite Songs</a>
                    <a href="{{ route('login') }}" class="menu-item" data-no-ajax="true">Favorite Artists</a>
                    <a href="{{ route('library.history') }}" class="menu-item {{ request()->routeIs('library.history') ? 'active' : '' }}">Listening History</a>
                @endauth
            </div>
            <div class="menu-group queue-group">
                <h3>DANH SÁCH CHỜ</h3>
                <div id="queue-list-container">
                    <div id="queue-list" style="color: #666; font-size: 13px; padding-left: 15px;">Chưa có bài hát nào...</div>
                </div>
            </div>
        </aside>

        <main class="content-area">
            {{ $slot }}
        </main>

        <aside class="sidebar-right">
            <h3 style="color: #fff; font-size: 16px; margin-bottom: 20px; text-transform: uppercase;">Lời Bài Hát</h3>
            <img id="rightCover" class="song-cover" src="{{ asset('image/default-cover.jpg') }}">
            <h4 id="rightTitle" style="color: #fff; margin-bottom: 5px;">Chưa chọn bài hát</h4>
            <div class="sidebar-artist-meta">
                <p id="rightArtist" class="sidebar-artist-text">--</p>
                <button type="button" id="sidebarFollowArtistBtn" class="sidebar-follow-artist-btn" style="display:none;">
                    <i class="fas fa-user-plus"></i>
                </button>
            </div>

            <div class="sidebar-panel-tabs">
                <button type="button" id="sidebarLyricsTabBtn" class="sidebar-panel-tab is-active" title="Lời bài hát">
                    <i class="fas fa-microphone"></i>
                </button>
                <button type="button" id="sidebarInfoTabBtn" class="sidebar-panel-tab" title="Thông tin bài hát">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div id="lyricsBox">
                <span class="lyrics-line">Hãy phát một bài hát</span>
                <span class="lyrics-line">để xem lời tại đây nhé!</span>
            </div>

            <div id="sidebarInfoPanel">
                <div class="sidebar-section-title">Thông tin bài hát</div>
                <div class="sidebar-info-card" id="sidebarQuickInfoCard">
                    <div class="sidebar-info-row"><span class="sidebar-info-label">Album</span><span class="sidebar-info-value">--</span></div>
                    <div class="sidebar-info-row"><span class="sidebar-info-label">Thể loại</span><span class="sidebar-info-value">--</span></div>
                    <div class="sidebar-info-row"><span class="sidebar-info-label">Lượt nghe</span><span class="sidebar-info-value">--</span></div>
                </div>

                <div class="sidebar-section-title">Bài hát cùng nghệ sĩ</div>
                <div class="sidebar-related-list" id="sidebarRelatedSongsList">
                    <div style="color:#8f95af;font-size:12px;">Chưa có dữ liệu.</div>
                </div>
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
                        <button type="button" id="playerPlaylistBtn" class="player-playlist-btn" title="Tùy chọn bài đang phát" style="border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.08); color: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0; height: 34px; min-width: 40px; padding: 0 12px; border-radius: 999px; font-weight: 800; font-size: 18px; line-height: 1; white-space: nowrap;">
                            <span aria-hidden="true">⋯</span>
                        </button>
                        <div id="playerPlaylistPopup" style="display:none; position: fixed; left: 50%; top: 50%; width: min(360px, calc(100vw - 32px)); background: #11141d; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.45); overflow: hidden; z-index: 1600; transform: translate(-50%, -50%) scale(0.98); opacity: 0;">
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
    let playlistTargetSong = null;

    if (typeof window.showToast !== 'function') {
        window.showToast = function(message, type = 'info') {
            if (!message) return;

            let stack = document.getElementById('appToastStack');
            if (!stack) {
                stack = document.createElement('div');
                stack.id = 'appToastStack';
                stack.className = 'app-toast-stack';
                document.body.appendChild(stack);
            }

            const toast = document.createElement('div');
            toast.className = `app-toast ${type}`;

            let iconClass = 'fa-info-circle';
            if (type === 'success') iconClass = 'fa-circle-check';
            if (type === 'error') iconClass = 'fa-circle-xmark';
            if (type === 'warning') iconClass = 'fa-triangle-exclamation';

            toast.innerHTML = `<i class="fas ${iconClass} app-toast-icon"></i><span>${String(message)}</span>`;
            stack.appendChild(toast);

            requestAnimationFrame(() => toast.classList.add('show'));

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 220);
            }, 2400);
        };
    }

    if (!window.__nativeAlert) {
        window.__nativeAlert = window.alert.bind(window);
        window.alert = function(message) {
            window.showToast(message || 'Đã có thông báo mới.', 'warning');
        };
    }

    function showLoginPrompt(message) {
        window.showToast(message || 'Vui lòng đăng nhập để sử dụng chức năng này.', 'warning');
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

    function getSongFromRow(row) {
        if (!row) return null;

        return {
            id: row.dataset.id,
            title: row.dataset.title,
            artist: row.dataset.artist,
            src: row.dataset.src,
            cover: row.dataset.cover,
            artistId: row.dataset.artistId,
            albumId: row.dataset.albumId
        };
    }

    function setPlaylistTargetSong(song) {
        playlistTargetSong = song && song.id ? song : null;
    }

    function getPlaylistTargetSong() {
        if (playlistTargetSong && playlistTargetSong.id) {
            return playlistTargetSong;
        }

        return typeof window.getCurrentSongData === 'function' ? window.getCurrentSongData() : null;
    }

    function openPlayerPlaylistPopup(song) {
        const popup = document.getElementById('playerPlaylistPopup');
        const toggleButton = document.getElementById('playerPlaylistBtn');
        const playlistList = document.getElementById('playerPlaylistList');

        if (!popup || !toggleButton || !playlistList) return;

        setPlaylistTargetSong(song && song.id ? song : null);

        popup.style.display = 'block';
        popup.style.opacity = '0';
        popup.style.transform = 'translate(-50%, -48%) scale(0.98)';
        popup.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
        requestAnimationFrame(() => {
            popup.style.opacity = '1';
            popup.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        if (typeof window.renderPlayerPlaylistList === 'function') {
            window.renderPlayerPlaylistList();
        }
    }

    function closePlayerPlaylistPopup() {
        const popup = document.getElementById('playerPlaylistPopup');
        if (!popup) return;

        popup.style.opacity = '0';
        popup.style.transform = 'translate(-50%, -48%) scale(0.98)';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 180);
        setPlaylistTargetSong(null);
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
                        const liked = (res.action === 'liked');
                        setLikeButtonState(button, liked);

                        if (button.dataset.removeOnUnlike === '1' && !liked) {
                            const songRow = button.closest('[data-liked-song-row]');
                            if (songRow) songRow.remove();

                            const songsTable = document.getElementById('likedSongsTable');
                            const songsEmpty = document.getElementById('likedSongsEmpty');
                            const hasRows = !!(songsTable && songsTable.querySelector('[data-liked-song-row]'));

                            if (songsTable) {
                                songsTable.style.display = hasRows ? 'table' : 'none';
                            }
                            if (songsEmpty) {
                                songsEmpty.style.display = hasRows ? 'none' : 'block';
                            }
                        }
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

    function bindSongPlaylistButtons() {
        document.querySelectorAll('.song-item-row').forEach((row) => {
            if (row.dataset.noPlaylistAdd === '1') return;

            const isTableRow = row.tagName === 'TR' && row.cells && row.cells.length > 0;
            let targetCell = null;
            let originalText = '';

            if (isTableRow) {
                targetCell = row.cells.length >= 4 ? row.cells[row.cells.length - 2] : row.cells[row.cells.length - 1];
                if (!targetCell || targetCell.querySelector('.song-add-playlist-btn')) return;
                if (targetCell.querySelector('button, a, input, select, textarea')) return;

                originalText = (targetCell.textContent || '').trim();
                targetCell.textContent = '';
                targetCell.style.textAlign = 'right';
                targetCell.style.width = '120px';
                targetCell.style.minWidth = '120px';
                targetCell.style.whiteSpace = 'nowrap';
            } else {
                if (row.querySelector('.song-add-playlist-btn')) return;
                targetCell = document.createElement('div');
                targetCell.className = 'song-playlist-action-slot';
            }

            const addButton = document.createElement('button');
            addButton.type = 'button';
            addButton.className = 'song-add-playlist-btn';
            addButton.title = 'Tùy chọn bài hát';
            addButton.innerHTML = '<span style="font-size: 16px; font-weight: 700; line-height: 1;">⋯</span>';

            addButton.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                const song = getSongFromRow(row);
                if (!song || !song.id) {
                    window.showToast('Không thể xác định bài hát này.', 'error');
                    return;
                }

                showSongContextMenu(event, song);
            });

            const durationWrap = document.createElement('span');
            durationWrap.className = 'song-duration-wrap';

            const durationText = document.createElement('span');
            durationText.className = 'song-duration-text';
            durationText.textContent = originalText;

            durationWrap.appendChild(addButton);

            if (isTableRow) {
                durationWrap.appendChild(durationText);
                targetCell.appendChild(durationWrap);
            } else {
                targetCell.appendChild(durationWrap);
                row.appendChild(targetCell);
            }
        });
    }

    async function showSongContextMenu(event, song) {
        const triggerElement = (event && (event.currentTarget || event.target)) ? (event.currentTarget || event.target) : null;
        const triggerRect = (triggerElement && typeof triggerElement.getBoundingClientRect === 'function')
            ? triggerElement.getBoundingClientRect()
            : {
                left: window.innerWidth / 2,
                right: window.innerWidth / 2,
                top: window.innerHeight / 2,
                height: 0,
                width: 0
            };

        // Xóa menu cũ nếu có
        const oldMenu = document.querySelector('.song-context-menu');
        if (oldMenu) oldMenu.remove();

        if (song && song.id && (!song.artistId || song.artistId === '' || typeof song.albumId === 'undefined')) {
            try {
                const response = await fetch(`/song/${song.id}`);
                if (response.ok) {
                    const detail = await response.json();
                    if (detail && detail.artist_id) {
                        song.artistId = String(detail.artist_id);
                    }
                    if (detail && Object.prototype.hasOwnProperty.call(detail, 'album_id')) {
                        song.albumId = detail.album_id ? String(detail.album_id) : '';
                    }
                }
            } catch (_) {
                // giữ nguyên dữ liệu cũ nếu không lấy được metadata
            }
        }

        const menu = document.createElement('div');
        menu.className = 'song-context-menu';

        // Thêm vào playlist
        const addPlaylistItem = document.createElement('button');
        addPlaylistItem.className = 'song-context-menu-item';
        addPlaylistItem.textContent = 'Thêm vào playlist';
        addPlaylistItem.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (!isAuthenticated) {
                showLoginPrompt('Vui lòng đăng nhập để thêm bài hát vào playlist.');
                return;
            }
            menu.remove();
            openPlayerPlaylistPopup(song);
        };
        menu.appendChild(addPlaylistItem);

        // Divider
        const divider1 = document.createElement('div');
        divider1.className = 'song-context-menu-divider';
        menu.appendChild(divider1);

        // Chuyển đến nghệ sĩ
        if (song.artistId) {
            const artistItem = document.createElement('button');
            artistItem.className = 'song-context-menu-item';
            artistItem.textContent = 'Chuyển đến nghệ sĩ này';
            artistItem.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                menu.remove();
                const targetUrl = `/artists/${song.artistId}`;
                if (typeof window.partialNavigate === 'function') {
                    window.partialNavigate(targetUrl);
                    return;
                }
                window.location.href = targetUrl;
            };
            menu.appendChild(artistItem);
        }

        // Chuyển đến album (nếu có)
        if (song.albumId) {
            const albumItem = document.createElement('button');
            albumItem.className = 'song-context-menu-item';
            albumItem.textContent = 'Chuyển đến album này';
            albumItem.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                menu.remove();
                const targetUrl = `/albums/${song.albumId}`;
                if (typeof window.partialNavigate === 'function') {
                    window.partialNavigate(targetUrl);
                    return;
                }
                window.location.href = targetUrl;
            };
            menu.appendChild(albumItem);
        }

        // Divider
        const divider2 = document.createElement('div');
        divider2.className = 'song-context-menu-divider';
        menu.appendChild(divider2);

        // Thêm vào danh sách chờ
        const queueItem = document.createElement('button');
        queueItem.className = 'song-context-menu-item';
        queueItem.textContent = 'Thêm vào danh sách chờ';
        queueItem.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            menu.remove();
            addToQueue(song.id);
        };
        menu.appendChild(queueItem);

        // Định vị menu cạnh nút ba chấm
        document.body.appendChild(menu);
        const rect = triggerRect;
        const menuRect = menu.getBoundingClientRect();
        const gap = 8;
        const viewportPadding = 10;

        let left = rect.right + gap;
        let top = rect.top + ((rect.height - menuRect.height) / 2);

        // Nếu thiếu chỗ bên phải, lật menu sang bên trái nút
        if (left + menuRect.width > window.innerWidth - viewportPadding) {
            left = rect.left - menuRect.width - gap;
        }

        // Giữ menu luôn nằm trong màn hình
        if (left < viewportPadding) {
            left = viewportPadding;
        }

        if (top < viewportPadding) {
            top = viewportPadding;
        }
        if (top + menuRect.height > window.innerHeight - viewportPadding) {
            top = Math.max(viewportPadding, window.innerHeight - menuRect.height - viewportPadding);
        }

        menu.style.left = left + 'px';
        menu.style.top = top + 'px';

        // Đóng menu khi click ngoài
        document.addEventListener('click', bindCloseMenu);
        function bindCloseMenu(e) {
            if (!menu.contains(e.target) && (!triggerElement || !triggerElement.contains(e.target))) {
                menu.remove();
                document.removeEventListener('click', bindCloseMenu);
            }
        }
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
                        const wasLiked = button.classList.contains('liked');
                        setLikeButtonState(button, !wasLiked);

                        if (button.dataset.removeOnUnlike === '1' && wasLiked) {
                            const artistCard = button.closest('[data-artist-card]');
                            if (artistCard) artistCard.remove();

                            const artistGrid = document.getElementById('likedArtistsGrid');
                            const emptyState = document.getElementById('likedArtistsEmpty');
                            const hasCards = !!(artistGrid && artistGrid.querySelector('[data-artist-card]'));

                            if (artistGrid) {
                                artistGrid.style.display = hasCards ? 'grid' : 'none';
                            }
                            if (emptyState) {
                                emptyState.style.display = hasCards ? 'none' : 'block';
                            }
                        }
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

        const playRandomButton = document.getElementById('btnPlayRandom');
        if (playRandomButton) {
            playRandomButton.onclick = function(event) {
                event.preventDefault();

                const rows = Array.from(document.querySelectorAll('#album-tracklist .song-item-row'));
                if (!rows.length) {
                    alert('Album này chưa có bài hát nào!');
                    return;
                }

                const randomRow = rows[Math.floor(Math.random() * rows.length)];
                const randomSongId = randomRow.dataset.id;

                if (!randomSongId) {
                    alert('Không thể phát bài hát này.');
                    return;
                }

                if (typeof window.playSong === 'function') {
                    window.playSong(randomSongId);
                }
            };
        }
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
                button.addEventListener('click', () => addTargetSongToPlaylist(button.dataset.playlistId));
            });
        };

        const getCurrentSong = () => getPlaylistTargetSong();

        const addTargetSongToPlaylist = async (playlistId) => {
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
                    closePlayerPlaylistPopup();
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

            if (popup.style.display === 'block') {
                closePlayerPlaylistPopup();
            }

            let currentSong = null;
            if (typeof window.getCurrentSongData === 'function') {
                currentSong = window.getCurrentSongData();
            }

            if (!currentSong || !currentSong.id) {
                currentSong = getPlaylistTargetSong();
            }

            if (!currentSong || !currentSong.id) {
                window.showToast('Vui lòng phát một bài hát trước.', 'warning');
                return;
            }

            showSongContextMenu(event, {
                id: currentSong.id,
                title: currentSong.title,
                artist: currentSong.artist,
                src: currentSong.src,
                cover: currentSong.cover,
                artistId: currentSong.artistId || '',
                albumId: currentSong.albumId || ''
            });
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
                alert('Vui lòng chọn một bài hát trước.');
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

                await addTargetSongToPlaylist(data.playlist.playlist_id);
            } catch (error) {
                alert('Lỗi kết nối, vui lòng thử lại.');
            }
        });

        document.addEventListener('click', function (event) {
            if (!popup.contains(event.target) && !toggleButton.contains(event.target)) {
                closePlayerPlaylistPopup();
            }
        });

        window.renderPlayerPlaylistList = renderPlayerPlaylistList;
        window.openPlaylistPopupForSong = openPlayerPlaylistPopup;
        window.closePlaylistPopup = closePlayerPlaylistPopup;
        window.setPlaylistTargetSong = setPlaylistTargetSong;
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
    bindSongPlaylistButtons();
    bindPlayerPlaylistMenu();
    bindSearchAutocomplete();
    bindAddToQueueButtons();

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

    function bindAddToQueueButtons() {
        document.querySelectorAll('.spotify-add-to-queue').forEach((button) => {
            const songId = button.dataset.id;
            if (!songId) return;

            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (typeof window.addToQueue === 'function') {
                    window.addToQueue(songId);
                } else {
                    alert('Chức năng thêm vào danh sách chờ không khả dụng.');
                }
            };
        });
    }

    function refreshPlayerBindings() {
        if (typeof window.buildSongList === 'function') {
            window.buildSongList();
        }
        if (typeof window.bindSongItemContextMenu === 'function') {
            window.bindSongItemContextMenu();
        }
        bindSongLikeButtons();
        bindArtistLikeButtons();
        bindDetailPlayButtons();
        bindSongPlaylistButtons();
        bindSearchAutocomplete();
        bindAddToQueueButtons();
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
                // Lưu queue container trước khi thay thế
                const queueContainer = currentSidebar.querySelector('#queue-list-container');
                const savedQueueHTML = queueContainer ? queueContainer.outerHTML : null;

                currentSidebar.innerHTML = nextSidebar.innerHTML;

                // Khôi phục queue container
                if (savedQueueHTML) {
                    const menuGroupQueue = currentSidebar.querySelector('.queue-group');
                    if (menuGroupQueue) {
                        menuGroupQueue.innerHTML = `
                            <h3>DANH SÁCH CHỜ</h3>
                            ${savedQueueHTML}
                        `;
                    }
                }
            }

            target.innerHTML = nextContent.innerHTML;
            target.scrollTop = 0;
            document.title = doc.title || document.title;

            requestAnimationFrame(() => {
                runScriptsIn(target);
                refreshPlayerBindings();
                // Gọi lại renderQueueSidebar để cập nhật UI queue từ localStorage
                if (typeof window.renderQueueSidebar === 'function') {
                    window.renderQueueSidebar();
                }
            });

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

    // ===== NOTIFICATION SYSTEM =====
    if (window.isAuthenticated) {
        const notiToggleBtn = document.getElementById('notiToggleBtn');
        const notiDropdown = document.getElementById('notiDropdown');
        const notiRedDot = document.getElementById('notiRedDot');
        const notiContent = document.getElementById('notiContent');
        const notiMarkAllRead = document.getElementById('notiMarkAllRead');

        console.log('Notification system initialized', { notiToggleBtn, notiDropdown, notiRedDot, notiContent, notiMarkAllRead });

        // Fetch notifications
        async function fetchNotifications() {
            try {
                console.log('Fetching notifications...');
                const response = await fetch('{{ route("api.notifications.get") }}');
                const data = await response.json();
                console.log('Notifications data:', data);
                
                // Update red dot visibility
                if (data.unread_count > 0) {
                    notiRedDot.style.display = 'block';
                } else {
                    notiRedDot.style.display = 'none';
                }

                // Render notifications
                if (data.notifications.length === 0) {
                    notiContent.innerHTML = '<p style="text-align: center; color: #888; padding: 20px;">Không có thông báo nào</p>';
                } else {
                    notiContent.innerHTML = data.notifications.map(noti => {
                        let iconClass = 'fa-info-circle';
                        if (noti.type === 'success') iconClass = 'fa-check-circle';
                        if (noti.type === 'warning') iconClass = 'fa-exclamation-circle';
                        if (noti.type === 'error') iconClass = 'fa-times-circle';

                        return `
                            <div class="noti-item ${noti.is_read ? '' : 'unread'}" data-noti-id="${noti.id}" data-noti-title="${noti.title.replace(/"/g, '&quot;')}" data-noti-content="${noti.content.replace(/"/g, '&quot;')}" data-noti-image="${noti.image_url || ''}" data-noti-type="${noti.type}" data-noti-date="${noti.created_at}" style="padding: 12px 15px; display: flex; gap: 12px; transition: 0.3s; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                ${noti.image_url ? `<img src="${noti.image_url}" alt="" class="noti-img" style="width: 45px; height: 45px; border-radius: 6px; object-fit: cover; flex-shrink: 0;">` : `<div class="noti-icon-box ${noti.type}" style="width: 45px; height: 45px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;"><i class="fas ${iconClass}"></i></div>`}
                                <div class="noti-text" style="flex: 1;">
                                    <p style="font-size: 13px; margin: 0 0 4px 0; color: #ddd; line-height: 1.4;"><b>${noti.title}</b></p>
                                    <p style="font-size: 12px; margin: 0; color: #888;">${noti.content.substring(0, 50)}${noti.content.length > 50 ? '...' : ''}</p>
                                    <span class="noti-time" style="font-size: 11px; color: rgba(255,255,255,0.5);">${noti.created_at}</span>
                                </div>
                            </div>
                        `;
                    }).join('');

                    // Add click handlers to notification items
                    document.querySelectorAll('.noti-item').forEach(item => {
                        item.addEventListener('click', async (e) => {
                            const notiId = item.dataset.notiId;
                            const notiTitle = item.dataset.notiTitle;
                            const notiContent = item.dataset.notiContent;
                            const notiImage = item.dataset.notiImage;
                            const notiType = item.dataset.notiType;
                            const notiDate = item.dataset.notiDate;
                            
                            console.log('Opening notification modal:', { notiId, notiTitle });
                            
                            // Show modal with full content
                            showNotificationModal({
                                id: notiId,
                                title: notiTitle,
                                content: notiContent,
                                image_url: notiImage,
                                type: notiType,
                                created_at: notiDate
                            });
                            
                            // Mark as read
                            await markAsRead(notiId);
                            item.classList.remove('unread');
                        });
                    });
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

        // Show notification modal
        function showNotificationModal(noti) {
            const modal = document.getElementById('notiModal');
            const notiModalTitle = document.getElementById('notiModalTitle');
            const notiModalMeta = document.getElementById('notiModalMeta');
            const notiModalBody = document.getElementById('notiModalBody');
            const notiModalImage = document.getElementById('notiModalImage');
            const notiModalImg = document.getElementById('notiModalImg');
            
            notiModalTitle.textContent = noti.title;
            notiModalMeta.textContent = noti.created_at;
            notiModalBody.innerHTML = noti.content.replace(/\n/g, '<br>');
            
            if (noti.image_url) {
                notiModalImage.style.display = 'block';
                notiModalImg.src = noti.image_url;
                notiModalImg.alt = noti.title;
            } else {
                notiModalImage.style.display = 'none';
            }
            
            modal.style.display = 'flex';
        }

        // Hide notification modal
        function hideNotificationModal() {
            const modal = document.getElementById('notiModal');
            modal.style.display = 'none';
        }

        // Mark notification as read
        async function markAsRead(notificationId) {
            try {
                const url = `{{ route('api.notifications.read', ':id') }}`.replace(':id', notificationId);
                console.log('Marking as read URL:', url);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Mark as read response:', response.status);
                if (response.ok) {
                    await fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Mark all as read
        if (notiMarkAllRead) {
            notiMarkAllRead.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                try {
                    const response = await fetch('{{ route("api.notifications.read-all") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    console.log('Mark all as read response:', response.status);
                    if (response.ok) {
                        await fetchNotifications();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            });
        }

        // Toggle notification dropdown
        if (notiToggleBtn) {
            notiToggleBtn.addEventListener('click', (e) => {
                console.log('Notification toggle clicked');
                e.stopPropagation();
                if (notiDropdown.classList.contains('show')) {
                    notiDropdown.classList.remove('show');
                } else {
                    notiDropdown.classList.add('show');
                    fetchNotifications();
                }
            });
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-wrapper')) {
                notiDropdown.classList.remove('show');
            }
        });

        // Modal close handlers
        const notiModal = document.getElementById('notiModal');
        const notiModalClose = document.getElementById('notiModalClose');
        
        if (notiModalClose) {
            notiModalClose.addEventListener('click', hideNotificationModal);
        }
        
        // Close modal when clicking outside the modal content
        if (notiModal) {
            notiModal.addEventListener('click', (e) => {
                if (e.target === notiModal) {
                    hideNotificationModal();
                }
            });
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && notiModal.style.display !== 'none') {
                hideNotificationModal();
            }
        });

        // Fetch notifications every 30 seconds
        setInterval(fetchNotifications, 30000);
        
        // Initial fetch
        fetchNotifications();
    }
</script>
</body>
</html>