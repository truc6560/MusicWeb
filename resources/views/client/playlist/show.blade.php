<x-client-layout>
    <style>
        .playlist-detail-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .playlist-detail-shell {
            position: relative;
            min-height: 100%;
            padding: 24px 24px 40px;
            border-radius: 0;
            overflow: visible;
            isolation: isolate;
            background: linear-gradient(180deg, rgba(var(--playlist-accent-rgb, 24, 27, 40), 0.68) 0%, rgba(var(--playlist-accent-rgb, 24, 27, 40), 0.38) 40%, #0b0d14 100%);
        }

        .playlist-detail-shell::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(4, 7, 14, 0.06) 0%, rgba(4, 7, 14, 0.18) 42%, rgba(4, 7, 14, 0.38) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .playlist-detail-shell > * {
            position: relative;
            z-index: 1;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            text-decoration: none;
            color: #d6e0f0;
            background: #141824;
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-weight: 700;
            font-size: 13px;
        }

        .playlist-title {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .playlist-layout {
            display: grid;
            grid-template-columns: minmax(280px, 3fr) minmax(0, 7fr);
            gap: 20px;
            align-items: start;
        }

        .panel {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 18px;
        }

        .panel h3 {
            font-size: 17px;
            margin-bottom: 12px;
        }

        .playlist-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 14px;
        }

        .playlist-info-head {
            display: grid;
            grid-template-columns: 88px 1fr;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
            padding: 12px;
            border-radius: 12px;
            background: #111523;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .playlist-cover-mini {
            width: 88px;
            height: 88px;
            border-radius: 10px;
            object-fit: cover;

        }

        .playlist-cover-wrap {
            position: relative;
            width: 88px;
            height: 88px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .playlist-cover-edit-btn {
            position: absolute;
            inset: 0;
            border: none;
            background: rgba(3, 10, 20, 0.52);
            color: #dff8ff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
            cursor: pointer;
            font-size: 16px;
            pointer-events: none;
        }
        
        .playlist-cover-wrap:hover .playlist-cover-edit-btn,
        .playlist-cover-wrap:focus-within .playlist-cover-edit-btn {
            opacity: 1;
            pointer-events: auto;
        }

        .playlist-meta-name {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.25;
            word-break: break-word;
        }

        .playlist-meta-name-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .playlist-meta-sub {
            margin-top: 6px;
            color: #9aa3b2;
            font-size: 13px;
        }

        .inline-edit-trigger {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: #1a2030;
            color: #cfe6ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .inline-edit-trigger:hover {
            border-color: rgba(0, 209, 255, 0.6);
            color: #8be8ff;
        }

        .inline-name-form {
            display: none;
            margin-top: 10px;
        }

        .inline-name-form.active {
            display: block;
        }

        .inline-name-input {
            width: 100%;
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 9px 11px;
            border-radius: 8px;
            outline: none;
        }

        .inline-name-hint {
            margin-top: 6px;
            color: #9aa3b2;
            font-size: 12px;
        }

        .cover-edit-row {
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #9aa3b2;
            font-size: 13px;
        }

        .playlist-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .playlist-action-secondary {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 999px;
            font-weight: 800;
        }

        .btn-playlist {
            flex: 1;
            background: linear-gradient(90deg, #00d1ff 0%, #31e7ff 100%);
            color: #042431;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-playlist:hover {
            filter: brightness(1.05);
        }

        .playlist-hero-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .playlist-hero-cover-frame {
            width: min(100%, 320px);
            aspect-ratio: 1 / 1;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .playlist-hero-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .playlist-hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 16px;
            width: 100%;
        }

        .playlist-action-round {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .playlist-action-round i {
            font-size: 14px;
        }

        .playlist-menu-wrap {
            position: relative;
            overflow: visible;
        }

        .playlist-menu-trigger {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(15, 18, 32, 0.92);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .playlist-menu-trigger:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 209, 255, 0.45);
            background: rgba(20, 26, 40, 0.98);
        }

        .playlist-action-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            min-width: 270px;
            max-height: min(340px, calc(100vh - 120px));
            background: #0f1220;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.35);
            padding: 8px;
            display: none;
            overflow-y: auto;
            z-index: 20;
        }

        .playlist-action-menu.open-up {
            top: auto;
            bottom: calc(100% + 10px);
        }

        .playlist-action-menu.open-down {
            top: calc(100% + 10px);
            bottom: auto;
        }

        .playlist-action-menu.open {
            display: block;
        }

        .playlist-action-menu button,
        .playlist-action-menu .menu-link {
            width: 100%;
            border: none;
            background: transparent;
            color: #edf2ff;
            padding: 11px 12px;
            border-radius: 10px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .playlist-action-menu button:hover,
        .playlist-action-menu .menu-link:hover {
            background: rgba(0, 209, 255, 0.08);
            color: #8be8ff;
        }

        .playlist-action-menu button i,
        .playlist-action-menu .menu-link i {
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .playlist-action-menu .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 6px 4px;
        }

        .playlist-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
        }

        .playlist-modal.open {
            display: flex;
        }

        .playlist-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(2, 5, 12, 0.72);
            backdrop-filter: blur(8px);
        }

        .playlist-modal-dialog {
            position: relative;
            width: min(100%, 420px);
            background: #131827;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.45);
        }

        .playlist-modal-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .playlist-modal-desc {
            color: #9aa3b2;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .playlist-copy-select {
            width: 100%;
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 10px 12px;
            border-radius: 10px;
            outline: none;
        }

        .playlist-modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .playlist-modal-actions .btn {
            flex: 1;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #edf2ff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .playlist-rename-input {
            width: 100%;
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 12px 14px;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
        }

        .playlist-rename-input:focus {
            border-color: rgba(0, 209, 255, 0.45);
            box-shadow: 0 0 0 3px rgba(0, 209, 255, 0.08);
        }

        .btn-danger-ghost {
            background: rgba(255, 88, 114, 0.12);
            border: 1px solid rgba(255, 88, 114, 0.35);
            color: #ffd9e0;
        }

        .btn-danger-ghost:hover {
            background: rgba(255, 88, 114, 0.18);
            border-color: rgba(255, 88, 114, 0.5);
        }

        .form-stack {
            display: none;
        }

        .field {
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 10px 12px;
            border-radius: 8px;
            outline: none;
            width: 100%;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(90deg, #00d1ff 0%, #bd00ff 100%);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid rgba(0, 209, 255, 0.5);
            color: #8be8ff;
        }

        .playlist-search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            min-height: 48px;
            padding: 0 14px;
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(18, 23, 38, 0.96) 0%, rgba(13, 17, 29, 0.96) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .playlist-search-icon {
            color: #7fdfff;
            font-size: 15px;
            flex-shrink: 0;
        }

        .playlist-search-field {
            border: none !important;
            background: transparent !important;
            appearance: none;
            -webkit-appearance: none;
            padding: 0;
            min-width: 0;
            height: 46px;
            color: #f4f7fb;
            font-size: 14px;
            outline: none;
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
            caret-color: #7fdfff;
        }

        .playlist-search-field:focus,
        .playlist-search-field:active {
            background: transparent !important;
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
        }

        .playlist-search-field:-webkit-autofill,
        .playlist-search-field:-webkit-autofill:hover,
        .playlist-search-field:-webkit-autofill:focus,
        .playlist-search-field:-webkit-autofill:active {
            -webkit-text-fill-color: #f4f7fb !important;
            box-shadow: 0 0 0 1000px rgba(13, 17, 29, 0.98) inset !important;
            -webkit-box-shadow: 0 0 0 1000px rgba(13, 17, 29, 0.98) inset !important;
            transition: background-color 9999s ease-out 0s;
        }

        .search-results {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 2px;
        }

        .search-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(12, 16, 28, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 10px 12px;
        }

        .search-cover {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .search-meta {
            min-width: 0;
            flex: 1;
        }

        .search-meta strong,
        .search-meta span {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-meta span {
            color: #9aa3b2;
            font-size: 13px;
        }

        .search-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .search-play-btn,
        .search-add-btn {
            white-space: nowrap;
        }

        .search-play-btn {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #00d1ff 0%, #0ab0de 100%);
            border: 1px solid rgba(10, 176, 222, 0.9);
            color: #041018;
            box-shadow: 0 8px 18px rgba(0, 209, 255, 0.34);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, background 0.18s ease;
        }

        .search-play-btn:hover {
            transform: translateY(-1px) scale(1.06);
            filter: brightness(1.08);
            box-shadow: 0 10px 22px rgba(0, 209, 255, 0.42);
            background: linear-gradient(135deg, #11dbff 0%, #00b7e6 100%);
        }

        .search-play-btn:active {
            transform: scale(0.97);
        }

        .search-play-btn.is-playing {
            color: #041018;
            box-shadow: 0 10px 24px rgba(0, 209, 255, 0.48);
        }

        .search-play-btn i {
            font-size: 11px;
            margin-left: 1px;
        }

        .search-play-wave {
            display: inline-flex;
            align-items: flex-end;
            gap: 2px;
            width: 14px;
            height: 12px;
        }

        .search-play-wave span {
            width: 2px;
            border-radius: 999px;
            background: currentColor;
            animation: songPlayWave 0.9s ease-in-out infinite;
            transform-origin: bottom center;
            opacity: 0.95;
        }

        .search-play-wave span:nth-child(1) { height: 5px; animation-delay: 0s; }
        .search-play-wave span:nth-child(2) { height: 10px; animation-delay: 0.12s; }
        .search-play-wave span:nth-child(3) { height: 7px; animation-delay: 0.24s; }

        .search-add-btn {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #dbe9ff;
        }

        .search-add-btn:hover {
            border-color: rgba(0, 209, 255, 0.55);
            color: #8be8ff;
            box-shadow: 0 8px 18px rgba(0, 209, 255, 0.12);
        }

        .search-add-btn i {
            font-size: 12px;
        }

        .search-cover-link {
            display: inline-flex;
            flex-shrink: 0;
        }

        .playlist-search-bar:focus-within {
            border-color: rgba(0, 209, 255, 0.42);
            box-shadow: 0 0 0 3px rgba(0, 209, 255, 0.08);
            transform: translateY(-1px);
        }

        .playlist-search-field::placeholder {
            color: #75839c;
        }

        .song-table {
            width: 100%;
            border-collapse: collapse;
        }

        .song-table th,
        .song-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #24293b;
            vertical-align: middle;
        }

        .song-table th {
            color: #8f95af;
            font-size: 12px;
            text-transform: uppercase;
        }

        .song-thumb {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }

        .song-title {
            font-weight: 700;
        }

        .song-artist {
            color: #a0a0a0;
            font-size: 13px;
        }

        .song-remove-btn {
            border: none;
            background: transparent;
            color: #ffffff;
            padding: 0;
            border-radius: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            transition: transform 0.18s ease, opacity 0.18s ease, color 0.18s ease;
        }

        .song-remove-btn:hover {
            color: #ffffff;
            transform: translateY(-1px) scale(1.06);
            opacity: 0.92;
        }

        .song-remove-btn i {
            font-size: 15px;
        }

        .empty-box {
            color: #8f95af;
            font-size: 14px;
            padding: 8px 0;
        }

        .alert {
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(0, 209, 255, 0.12);
            border: 1px solid rgba(0, 209, 255, 0.35);
            color: #8eefff;
        }

        @media (max-width: 1000px) {
            .playlist-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="playlist-detail-shell" id="playlistDetailShell">
        <div class="playlist-detail-top">
            <a class="back-btn" href="{{ route('playlist.index') }}" data-no-ajax="false">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại danh sách playlist</span>
            </a>
        </div>

        <h1 class="playlist-title">{{ $playlist->name }}</h1>

        @if(session('success'))
            <div id="playlistLegacySuccess" class="alert alert-success" style="display:none;">{{ session('success') }}</div>
        @endif

        <div class="playlist-layout">
            <div class="panel playlist-hero-panel">
                <div class="playlist-hero-cover-frame">
                    <img id="playlistCoverImage" src="{{ $playlist->cover_url }}" class="playlist-hero-cover" alt="{{ $playlist->name }}">
                </div>

                <div class="playlist-hero-actions">
                    <button id="playlistPlayBtn" class="btn btn-playlist playlist-action-round" type="button" title="Phát playlist" aria-label="Phát playlist">
                        <i class="fas fa-play"></i>
                    </button>

                    <button id="playlistRandomBtn" class="btn btn-playlist playlist-action-round" type="button" title="Phát ngẫu nhiên" aria-label="Phát ngẫu nhiên">
                        <i class="fas fa-random"></i>
                    </button>

                    <div class="playlist-menu-wrap">
                        <button id="playlistMenuBtn" class="playlist-menu-trigger" type="button" title="Tùy chọn playlist" aria-label="Tùy chọn playlist">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>

                        <div id="playlistActionMenu" class="playlist-action-menu" aria-hidden="true">
                            <button type="button" data-action="choose-cover"><i class="fas fa-image"></i><span>Chọn ảnh playlist</span></button>
                            <button type="button" data-action="rename"><i class="fas fa-pen"></i><span>Đổi tên playlist</span></button>
                            <button type="button" data-action="delete"><i class="fas fa-trash-alt"></i><span>Xóa toàn bộ playlist</span></button>
                            <button type="button" data-action="toggle-visibility" id="playlistVisibilityButton"><i class="fas fa-lock"></i><span id="playlistVisibilityLabel">Chuyển sang riêng tư</span></button>
                            <div class="menu-divider"></div>
                            <button type="button" data-action="copy-songs"><i class="fas fa-clone"></i><span>Thêm bài hát sang playlist khác</span></button>
                        </div>
                    </div>
                </div>

                <form id="playlistRenameForm" class="form-stack" method="POST" action="{{ route('playlist.update', ['id' => $playlist->playlist_id]) }}">
                    @csrf
                    <input id="playlistNameInput" class="field" type="text" name="name" value="{{ $playlist->name }}" maxlength="255" required>
                </form>

                <form id="playlistCoverForm" class="form-stack" method="POST" action="{{ route('playlist.update', ['id' => $playlist->playlist_id]) }}" enctype="multipart/form-data">
                    @csrf
                    <input id="playlistCoverInput" class="field" type="file" name="cover_image" accept="image/*" required>
                </form>
            </div>

            <div class="panel">
                <h3>Bài hát trong playlist (<span id="playlistSongCount">{{ $playlist->songs->count() }}</span>)</h3>
                <div class="playlist-search-bar">
                    <i class="fas fa-search playlist-search-icon"></i>
                    <input id="playlistSongSearch" class="field playlist-search-field" type="text" placeholder="Tìm tên bài hát hoặc nghệ sĩ...">
                </div>
                <div id="playlistSearchResults" class="search-results" style="margin-bottom: 16px;">
                    <div class="empty-box">Nhập từ khóa để tìm bài hát.</div>
                </div>

                @if($playlist->songs->isEmpty())
                    <div id="playlistSongsEmpty" class="empty-box">Playlist này chưa có bài hát nào.</div>
                @else
                    <table class="song-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bài hát</th>
                                <th style="text-align: right;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($playlist->songs as $idx => $song)
                                @php
                                    $cover = $song->image_url ?: asset('image/default-cover.jpg');
                                @endphp
                                <tr class="song-item-row"
                                    data-playlist-song-row="{{ $song->song_id }}"
                                    data-no-playlist-add="1"
                                    data-id="{{ $song->song_id }}"
                                    data-title="{{ $song->title }}"
                                    data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                    data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                                    data-cover="{{ $cover }}">
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #06131a; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-play" style="font-size: 11px;"></i>
                                            </button>
                                            <a href="{{ route('song.details', ['id' => $song->song_id]) }}" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                                                <img src="{{ $cover }}" class="song-thumb" alt="{{ $song->title }}">
                                            </a>
                                            <div>
                                                <div class="song-title">{{ $song->title }}</div>
                                                <div class="song-artist">{{ $song->artist->name ?? 'Unknown Artist' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" class="song-remove-btn btn-remove-playlist-song" data-song-id="{{ $song->song_id }}" title="Xóa bài hát khỏi playlist" aria-label="Xóa bài hát khỏi playlist">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div id="playlistCopyModal" class="playlist-modal" aria-hidden="true">
        <div class="playlist-modal-backdrop" data-modal-close="1"></div>
        <div class="playlist-modal-dialog">
            <div class="playlist-modal-title">Thêm toàn bộ bài hát sang playlist khác</div>
            <div class="playlist-modal-desc">Chọn playlist đích để sao chép toàn bộ bài hát hiện có.</div>

            <form id="playlistCopyForm">
                <select id="playlistCopyTarget" class="playlist-copy-select" required>
                    <option value="">-- Chọn playlist đích --</option>
                    @forelse($otherPlaylists as $otherPlaylist)
                        <option value="{{ $otherPlaylist->playlist_id }}">{{ $otherPlaylist->name }} ({{ $otherPlaylist->songs_count }} bài hát)</option>
                    @empty
                        <option value="" disabled>Không có playlist nào khác</option>
                    @endforelse
                </select>

                <div class="playlist-modal-actions">
                    <button class="btn btn-secondary" type="button" data-modal-close="1">Hủy</button>
                    <button class="btn btn-primary" type="submit" id="playlistCopySubmitBtn">Thêm toàn bộ</button>
                </div>
            </form>
        </div>
    </div>

    <div id="playlistRenameModal" class="playlist-modal" aria-hidden="true">
        <div class="playlist-modal-backdrop" data-rename-close="1"></div>
        <div class="playlist-modal-dialog">
            <div class="playlist-modal-title">Đổi tên playlist</div>
            <div class="playlist-modal-desc">Nhập tên mới cho playlist rồi bấm lưu.</div>

            <form id="playlistRenameModalForm">
                <input id="playlistRenameModalInput" class="playlist-rename-input" type="text" maxlength="255" required>

                <div class="playlist-modal-actions">
                    <button class="btn btn-secondary" type="button" data-rename-close="1">Hủy</button>
                    <button class="btn btn-primary" type="submit" id="playlistRenameSubmitBtn">Lưu tên</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const searchInput = document.getElementById('playlistSongSearch');
            const searchResults = document.getElementById('playlistSearchResults');
            const legacySuccess = document.getElementById('playlistLegacySuccess');
            const playlistSongsTable = document.querySelector('.song-table');
            const playlistSongCount = document.getElementById('playlistSongCount');
            const playlistPlayBtn = document.getElementById('playlistPlayBtn');
            const playlistRandomBtn = document.getElementById('playlistRandomBtn');
            const playlistMenuBtn = document.getElementById('playlistMenuBtn');
            const playlistActionMenu = document.getElementById('playlistActionMenu');
            const playlistVisibilityButton = document.getElementById('playlistVisibilityButton');
            const playlistVisibilityLabel = document.getElementById('playlistVisibilityLabel');
            const renameForm = document.getElementById('playlistRenameForm');
            const coverForm = document.getElementById('playlistCoverForm');
            const playlistNameInput = document.getElementById('playlistNameInput');
            const playlistCoverInput = document.getElementById('playlistCoverInput');
            const playlistCoverImage = document.getElementById('playlistCoverImage');
            const playlistDetailShell = document.getElementById('playlistDetailShell');
            const playlistCopyModal = document.getElementById('playlistCopyModal');
            const playlistCopyForm = document.getElementById('playlistCopyForm');
            const playlistCopyTarget = document.getElementById('playlistCopyTarget');
            const playlistCopySubmitBtn = document.getElementById('playlistCopySubmitBtn');
            const playlistRenameModal = document.getElementById('playlistRenameModal');
            const playlistRenameModalForm = document.getElementById('playlistRenameModalForm');
            const playlistRenameModalInput = document.getElementById('playlistRenameModalInput');
            const playlistRenameSubmitBtn = document.getElementById('playlistRenameSubmitBtn');
            const playlistTitle = document.querySelector('.playlist-title');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const searchUrl = @json(route('playlist.search-songs', ['id' => $playlist->playlist_id]));
            const addSongUrl = @json(route('playlist.add-song'));
            const removeSongUrl = @json(route('playlist.remove-song', ['id' => $playlist->playlist_id]));
            const deletePlaylistUrl = @json(route('playlist.destroy', ['id' => $playlist->playlist_id]));
            const visibilityPlaylistUrl = @json(route('playlist.visibility', ['id' => $playlist->playlist_id]));
            const copySongsUrl = @json(route('playlist.copy-songs', ['id' => $playlist->playlist_id]));
            const playlistIndexUrl = @json(route('playlist.index'));
            const updatePlaylistUrl = @json(route('playlist.update', ['id' => $playlist->playlist_id]));
            let searchDebounce = null;
            const playlistIsPublic = @json((bool) $playlist->is_public);

            const applyPlaylistAccentFromCover = () => {
                if (!playlistDetailShell || !playlistCoverImage || !playlistCoverImage.complete || !playlistCoverImage.naturalWidth) {
                    return;
                }

                try {
                    const canvas = document.createElement('canvas');
                    const size = 32;
                    canvas.width = size;
                    canvas.height = size;

                    const context = canvas.getContext('2d', { willReadFrequently: true });
                    if (!context) return;

                    context.drawImage(playlistCoverImage, 0, 0, size, size);
                    const imageData = context.getImageData(0, 0, size, size).data;

                    let red = 0;
                    let green = 0;
                    let blue = 0;
                    let samples = 0;

                    for (let index = 0; index < imageData.length; index += 4) {
                        const alpha = imageData[index + 3];
                        if (alpha < 32) continue;
                        red += imageData[index];
                        green += imageData[index + 1];
                        blue += imageData[index + 2];
                        samples += 1;
                    }

                    if (!samples) return;

                    red = Math.round(red / samples);
                    green = Math.round(green / samples);
                    blue = Math.round(blue / samples);

                    playlistDetailShell.style.setProperty('--playlist-accent-rgb', `${red}, ${green}, ${blue}`);
                } catch (error) {
                    playlistDetailShell.style.setProperty('--playlist-accent-rgb', '24, 27, 40');
                }
            };

            if (playlistCoverImage) {
                if (playlistCoverImage.complete) {
                    applyPlaylistAccentFromCover();
                } else {
                    playlistCoverImage.addEventListener('load', applyPlaylistAccentFromCover, { once: true });
                }
            }

            if (playlistVisibilityLabel) {
                playlistVisibilityLabel.textContent = playlistIsPublic ? 'Chuyển sang riêng tư' : 'Chuyển sang công khai';
            }

            if (!searchInput || !searchResults) return;

            const renderEmpty = (text) => {
                searchResults.innerHTML = `<div class="empty-box">${text}</div>`;
                if (typeof window.syncPlaylistSearchPlayButtons === 'function') {
                    window.syncPlaylistSearchPlayButtons();
                }
            };

            const getSearchPlayButtonHtml = (isPlaying) => {
                return isPlaying
                    ? '<span class="search-play-wave" aria-hidden="true"><span></span><span></span><span></span></span>'
                    : '<i class="fas fa-play"></i>';
            };

            const syncPlaylistSearchPlayButtons = () => {
                const snapshot = typeof window.getCurrentPlaybackSnapshot === 'function'
                    ? window.getCurrentPlaybackSnapshot()
                    : { songId: null, state: 'paused' };

                const activeSongId = snapshot.songId ? String(snapshot.songId) : null;
                const isPlaying = activeSongId && snapshot.state === 'playing';

                searchResults.querySelectorAll('.btn-play-search-song').forEach((button) => {
                    const buttonSongId = button.dataset.songId ? String(button.dataset.songId) : null;
                    const shouldAnimate = Boolean(isPlaying && buttonSongId === activeSongId);
                    button.classList.toggle('is-playing', shouldAnimate);
                    button.innerHTML = getSearchPlayButtonHtml(shouldAnimate);
                });
            };

            window.syncPlaylistSearchPlayButtons = syncPlaylistSearchPlayButtons;

            if (legacySuccess && typeof window.showToast === 'function') {
                window.showToast(legacySuccess.textContent || 'Cập nhật thành công.', 'success');
            }

            const positionPlaylistMenu = () => {
                if (!playlistActionMenu) return;
                playlistActionMenu.classList.remove('open-up', 'open-down');

                const triggerRect = playlistMenuBtn ? playlistMenuBtn.getBoundingClientRect() : null;
                const menuHeight = playlistActionMenu.offsetHeight || 0;
                const spaceBelow = triggerRect ? window.innerHeight - triggerRect.bottom : 0;
                const spaceAbove = triggerRect ? triggerRect.top : 0;
                const shouldOpenUp = menuHeight > 0 && spaceBelow < menuHeight + 16 && spaceAbove > spaceBelow;

                playlistActionMenu.classList.add(shouldOpenUp ? 'open-up' : 'open-down');
            };

            const openPlaylistMenu = () => {
                if (!playlistActionMenu) return;
                playlistActionMenu.classList.add('open');
                playlistActionMenu.setAttribute('aria-hidden', 'false');
                requestAnimationFrame(positionPlaylistMenu);
            };

            const closePlaylistMenu = () => {
                if (!playlistActionMenu) return;
                playlistActionMenu.classList.remove('open');
                playlistActionMenu.setAttribute('aria-hidden', 'true');
            };

            const openCopyModal = () => {
                if (!playlistCopyModal) return;
                playlistCopyModal.classList.add('open');
                playlistCopyModal.setAttribute('aria-hidden', 'false');
                if (playlistCopyTarget) playlistCopyTarget.focus();
            };

            const closeCopyModal = () => {
                if (!playlistCopyModal) return;
                playlistCopyModal.classList.remove('open');
                playlistCopyModal.setAttribute('aria-hidden', 'true');
            };

            const openRenameModal = () => {
                if (!playlistRenameModal || !playlistRenameModalInput) return;
                playlistRenameModal.classList.add('open');
                playlistRenameModal.setAttribute('aria-hidden', 'false');
                playlistRenameModalInput.value = playlistTitle ? playlistTitle.textContent.trim() : '';
                setTimeout(() => {
                    playlistRenameModalInput.focus();
                    playlistRenameModalInput.select();
                }, 0);
            };

            const closeRenameModal = () => {
                if (!playlistRenameModal) return;
                playlistRenameModal.classList.remove('open');
                playlistRenameModal.setAttribute('aria-hidden', 'true');
            };

            const playRandomSong = () => {
                const rows = Array.from(document.querySelectorAll('[data-playlist-song-row].song-item-row'));
                if (!rows.length) {
                    alert('Playlist này chưa có bài hát để phát.');
                    return;
                }

                const randomRow = rows[Math.floor(Math.random() * rows.length)];
                const randomSong = {
                    id: randomRow.dataset.id,
                    title: randomRow.dataset.title,
                    artist: randomRow.dataset.artist,
                    src: randomRow.dataset.src,
                    cover: randomRow.dataset.cover,
                };

                if (!randomSong.id || !randomSong.src) {
                    alert('Không thể phát bài hát ngẫu nhiên do thiếu dữ liệu.');
                    return;
                }

                const shuffleBtnUI = document.getElementById('shuffleBtn');
                if (shuffleBtnUI) {
                    shuffleBtnUI.classList.add('active');
                }

                if (typeof window.buildSongList === 'function') {
                    window.buildSongList();
                }

                if (typeof window.loadSong === 'function') {
                    window.loadSong(randomSong, true);
                    if (typeof window.updateRowHighlight === 'function') {
                        window.updateRowHighlight(randomSong.id);
                    }
                }
            };

            if (playlistMenuBtn && playlistActionMenu) {
                playlistMenuBtn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const isOpen = playlistActionMenu.classList.contains('open');
                    if (isOpen) {
                        closePlaylistMenu();
                    } else {
                        openPlaylistMenu();
                    }
                });
            }

            window.addEventListener('resize', () => {
                if (playlistActionMenu && playlistActionMenu.classList.contains('open')) {
                    positionPlaylistMenu();
                }
            });

            window.addEventListener('scroll', () => {
                if (playlistActionMenu && playlistActionMenu.classList.contains('open')) {
                    positionPlaylistMenu();
                }
            }, true);

            document.addEventListener('click', (event) => {
                if (playlistActionMenu && playlistMenuBtn && !playlistMenuBtn.contains(event.target) && !playlistActionMenu.contains(event.target)) {
                    closePlaylistMenu();
                }
            });

            if (playlistActionMenu) {
                playlistActionMenu.addEventListener('click', async (event) => {
                    const button = event.target.closest('[data-action]');
                    if (!button) return;

                    const action = button.dataset.action;
                    closePlaylistMenu();

                    if (action === 'choose-cover') {
                        if (playlistCoverInput) playlistCoverInput.click();
                        return;
                    }

                    if (action === 'rename') {
                        openRenameModal();
                        return;
                    }

                    if (action === 'delete') {
                        if (!confirm('Xóa toàn bộ playlist này? Hành động này không thể hoàn tác.')) return;
                        try {
                            const response = await fetch(deletePlaylistUrl, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                            });

                            const data = await response.json();
                            if (response.ok && data.status === 'success') {
                                window.location.href = data.redirect_url || playlistIndexUrl;
                                return;
                            }

                            alert(data.message || 'Không thể xóa playlist.');
                        } catch (error) {
                            alert('Lỗi kết nối, vui lòng thử lại.');
                        }
                        return;
                    }

                    if (action === 'toggle-visibility') {
                        try {
                            const response = await fetch(visibilityPlaylistUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ is_public: !playlistIsPublic }),
                            });

                            const data = await response.json();
                            if (response.ok && data.status === 'success') {
                                window.location.reload();
                                return;
                            }

                            alert(data.message || 'Không thể thay đổi trạng thái playlist.');
                        } catch (error) {
                            alert('Lỗi kết nối, vui lòng thử lại.');
                        }
                        return;
                    }

                    if (action === 'copy-songs') {
                        openCopyModal();
                    }
                });
            }

            if (playlistRandomBtn) {
                playlistRandomBtn.addEventListener('click', playRandomSong);
            }

            if (playlistCopyModal) {
                playlistCopyModal.addEventListener('click', (event) => {
                    if (event.target && event.target.dataset && event.target.dataset.modalClose === '1') {
                        closeCopyModal();
                    }
                });
            }

            if (playlistRenameModal) {
                playlistRenameModal.addEventListener('click', (event) => {
                    if (event.target && event.target.dataset && event.target.dataset.renameClose === '1') {
                        closeRenameModal();
                    }
                });
            }

            if (playlistRenameModalForm && playlistRenameModalInput) {
                playlistRenameModalForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const nextName = (playlistRenameModalInput.value || '').trim();
                    if (!nextName) {
                        alert('Tên playlist không được để trống.');
                        return;
                    }

                    if (playlistNameInput) playlistNameInput.value = nextName;

                    if (playlistRenameSubmitBtn) {
                        playlistRenameSubmitBtn.disabled = true;
                        playlistRenameSubmitBtn.textContent = 'Đang lưu...';
                    }

                    try {
                        await submitPlaylistUpdate(renameForm);
                        closeRenameModal();
                    } catch (error) {
                        alert(error.message || 'Không thể cập nhật tên playlist.');
                    } finally {
                        if (playlistRenameSubmitBtn) {
                            playlistRenameSubmitBtn.disabled = false;
                            playlistRenameSubmitBtn.textContent = 'Lưu tên';
                        }
                    }
                });
            }

            if (playlistCopyForm && playlistCopyTarget) {
                playlistCopyForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const targetPlaylistId = playlistCopyTarget.value;
                    if (!targetPlaylistId) {
                        alert('Vui lòng chọn playlist đích.');
                        return;
                    }

                    if (playlistCopySubmitBtn) {
                        playlistCopySubmitBtn.disabled = true;
                        playlistCopySubmitBtn.textContent = 'Đang thêm...';
                    }

                    try {
                        const response = await fetch(copySongsUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ target_playlist_id: Number(targetPlaylistId) }),
                        });

                        const data = await response.json();
                        if (response.ok && data.status === 'success') {
                            closeCopyModal();
                            if (typeof window.showToast === 'function') {
                                window.showToast(data.message || 'Đã thêm toàn bộ bài hát.', 'success');
                            } else {
                                alert(data.message || 'Đã thêm toàn bộ bài hát.');
                            }
                            return;
                        }

                        alert(data.message || 'Không thể thêm bài hát sang playlist khác.');
                    } catch (error) {
                        alert('Lỗi kết nối, vui lòng thử lại.');
                    } finally {
                        if (playlistCopySubmitBtn) {
                            playlistCopySubmitBtn.disabled = false;
                            playlistCopySubmitBtn.textContent = 'Thêm toàn bộ';
                        }
                    }
                });
            }

            const showSuccess = (message) => {
                if (typeof window.showToast === 'function') {
                    window.showToast(message || 'Cập nhật thành công.', 'success');
                    return;
                }
                alert(message || 'Cập nhật thành công.');
            };

            const submitPlaylistUpdate = async (formElement) => {
                if (!formElement) return;
                const formData = new FormData(formElement);

                const response = await fetch(updatePlaylistUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();
                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Không thể cập nhật playlist.');
                }

                if (data.playlist?.name) {
                    if (playlistTitle) playlistTitle.textContent = data.playlist.name;
                    if (playlistNameInput) playlistNameInput.value = data.playlist.name;
                }

                if (data.playlist?.cover_url && playlistCoverImage) {
                    const cacheBustedCover = `${data.playlist.cover_url}${data.playlist.cover_url.includes('?') ? '&' : '?'}t=${Date.now()}`;
                    playlistCoverImage.src = cacheBustedCover;
                    playlistCoverImage.addEventListener('load', applyPlaylistAccentFromCover, { once: true });
                }

                showSuccess(data.message || 'Đã cập nhật playlist.');
            };

            if (renameForm) {
                renameForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    try {
                        await submitPlaylistUpdate(renameForm);
                    } catch (error) {
                        alert(error.message || 'Không thể cập nhật tên playlist.');
                    }
                });
            }

            if (coverForm) {
                coverForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    try {
                        await submitPlaylistUpdate(coverForm);
                    } catch (error) {
                        alert(error.message || 'Không thể cập nhật ảnh playlist.');
                    }
                });
            }

            if (playlistCoverInput) {
                playlistCoverInput.addEventListener('change', async () => {
                    if (!playlistCoverInput.files || !playlistCoverInput.files.length) {
                        return;
                    }

                    try {
                        await submitPlaylistUpdate(coverForm);
                    } catch (error) {
                        alert(error.message || 'Không thể cập nhật ảnh playlist.');
                    } finally {
                        playlistCoverInput.value = '';
                    }
                });
            }

            const runSearch = async () => {
                const query = (searchInput.value || '').trim();
                if (query.length < 2) {
                    renderEmpty('Nhập ít nhất 2 ký tự để xem gợi ý bài hát.');
                    return;
                }

                renderEmpty('Đang tìm kiếm...');

                try {
                    const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (!response.ok || data.status !== 'success') {
                        renderEmpty(data.message || 'Không thể tìm kiếm lúc này.');
                        return;
                    }

                    if (!Array.isArray(data.results) || data.results.length === 0) {
                        renderEmpty('Không tìm thấy bài hát phù hợp hoặc bài hát đã có trong playlist.');
                        return;
                    }

                    searchResults.innerHTML = data.results.map((item) => `
                        <div class="search-item">
                            <a class="search-cover-link" href="${item.details_url}" data-no-ajax="false">
                                <img class="search-cover" src="${item.cover}" alt="${item.title}">
                            </a>
                            <div class="search-meta">
                                <strong>${item.title}</strong>
                                <span>${item.artist_name}</span>
                            </div>
                            <div class="search-actions">
                                ${item.in_playlist
                                    ? `<button class="btn btn-outline search-play-btn btn-play-search-song" type="button" title="Phát nhạc" aria-label="Phát nhạc" data-song-id="${item.song_id}" data-song-src="${item.stream_url}" data-song-title="${item.title}" data-song-artist="${item.artist_name}" data-song-cover="${item.cover}"><i class="fas fa-play"></i></button>`
                                    : `
                                        <button class="btn btn-outline search-play-btn btn-play-search-song" type="button" title="Phát nhạc" aria-label="Phát nhạc" data-song-id="${item.song_id}" data-song-src="${item.stream_url}" data-song-title="${item.title}" data-song-artist="${item.artist_name}" data-song-cover="${item.cover}"><i class="fas fa-play"></i></button>
                                        <button class="btn btn-outline search-add-btn btn-add-search-song" type="button" title="Thêm nhạc" aria-label="Thêm nhạc" data-song-id="${item.song_id}"><i class="fas fa-plus"></i></button>
                                    `}
                            </div>
                        </div>
                    `).join('');
                    syncPlaylistSearchPlayButtons();
                } catch (error) {
                    renderEmpty('Lỗi kết nối, vui lòng thử lại.');
                }
            };

            searchInput.addEventListener('input', () => {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(runSearch, 280);
            });
            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    runSearch();
                }
            });

            searchResults.addEventListener('click', (event) => {
                const playButton = event.target.closest('.btn-play-search-song');
                if (!playButton) return;

                const song = {
                    id: playButton.dataset.songId,
                    title: playButton.dataset.songTitle,
                    artist: playButton.dataset.songArtist,
                    src: playButton.dataset.songSrc,
                    cover: playButton.dataset.songCover,
                };

                if (!song.id || !song.src) {
                    alert('Không thể phát bài hát này.');
                    return;
                }

                if (typeof window.buildSongList === 'function') {
                    window.buildSongList();
                }

                if (typeof window.loadSong === 'function') {
                    window.loadSong(song, true);
                    if (typeof window.updateRowHighlight === 'function') {
                        window.updateRowHighlight(song.id);
                    }
                    setTimeout(syncPlaylistSearchPlayButtons, 0);
                }
            });

            searchResults.addEventListener('click', async (event) => {
                const button = event.target.closest('.btn-add-search-song');
                if (!button) return;

                const songId = button.dataset.songId;
                if (!songId) return;

                button.disabled = true;
                button.textContent = 'Đang thêm...';

                try {
                    const response = await fetch(addSongUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            playlist_id: {{ (int) $playlist->playlist_id }},
                            song_id: Number(songId),
                        }),
                    });

                    const data = await response.json();
                    if (response.ok && data.status === 'success') {
                        if (typeof window.partialNavigate === 'function') {
                            await window.partialNavigate(window.location.href, { fromPopState: true });
                        } else {
                            window.location.reload();
                        }
                        return;
                    }

                    alert(data.message || 'Không thể thêm bài hát vào playlist.');
                } catch (error) {
                    alert('Lỗi kết nối, vui lòng thử lại.');
                } finally {
                    button.disabled = false;
                    button.textContent = 'Thêm nhạc';
                }
            });

            if (playlistPlayBtn) {
                playlistPlayBtn.addEventListener('click', () => {
                    const rows = Array.from(document.querySelectorAll('[data-playlist-song-row].song-item-row'));
                    if (!rows.length) {
                        alert('Playlist này chưa có bài hát để phát.');
                        return;
                    }

                    const first = rows[0];
                    const song = {
                        id: first.dataset.id,
                        title: first.dataset.title,
                        artist: first.dataset.artist,
                        src: first.dataset.src,
                        cover: first.dataset.cover
                    };

                    if (!song.id || !song.src) {
                        alert('Không thể phát playlist do thiếu dữ liệu bài hát.');
                        return;
                    }

                    if (typeof window.buildSongList === 'function') {
                        window.buildSongList();
                    }

                    if (typeof window.loadSong === 'function') {
                        window.loadSong(song, true);
                        if (typeof window.updateRowHighlight === 'function') {
                            window.updateRowHighlight(song.id);
                        }
                    }
                });
            }

            document.addEventListener('click', async (event) => {
                const button = event.target.closest('.btn-remove-playlist-song');
                if (!button) return;

                const songId = button.dataset.songId;
                if (!songId) return;

                if (!confirm('Xóa bài hát này khỏi playlist?')) return;

                button.disabled = true;
                button.textContent = 'Đang xóa...';

                try {
                    const response = await fetch(removeSongUrl, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ song_id: Number(songId) }),
                    });

                    const data = await response.json();
                    if (response.ok && data.status === 'success') {
                        const row = button.closest('[data-playlist-song-row]');
                        if (row) row.remove();

                        const remainingRows = document.querySelectorAll('[data-playlist-song-row]');
                        if (playlistSongCount) {
                            playlistSongCount.textContent = String(Math.max(0, remainingRows.length));
                        }

                        if (playlistSongsTable && remainingRows.length === 0) {
                            playlistSongsTable.insertAdjacentHTML('afterend', '<div class="empty-box">Playlist này chưa có bài hát nào.</div>');
                            playlistSongsTable.remove();
                        }

                        showSuccess(data.message || 'Đã xóa bài hát khỏi playlist.');
                        return;
                    }

                    alert(data.message || 'Không thể xóa bài hát khỏi playlist.');
                } catch (error) {
                    alert('Lỗi kết nối, vui lòng thử lại.');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash-alt"></i><span>Xóa</span>';
                }
            });

        })();
    </script>
</x-client-layout>
