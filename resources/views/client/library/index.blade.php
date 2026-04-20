<x-client-layout>
    <style>
        .library-header {
            margin-bottom: 24px;
        }

        .library-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .library-subtitle {
            color: #a0a0a0;
            font-size: 14px;
        }

        .library-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .library-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #161822;
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: #a0a0a0;
            text-decoration: none;
            font-weight: 600;
        }

        .library-tab.active {
            color: #fff;
            border-color: rgba(0, 209, 255, 0.45);
            background: rgba(0, 209, 255, 0.12);
        }

        .library-panel {
            background: #161822;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 20px;
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

        .song-artist,
        .song-meta {
            color: #a0a0a0;
            font-size: 13px;
        }

        .history-time-col {
            text-align: right;
            white-space: nowrap;
        }

        .song-unlike-btn {
            border: none;
            background: transparent;
            color: #ffffff;
            border-radius: 0;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            transition: transform 0.18s ease, opacity 0.18s ease, color 0.18s ease;
            white-space: nowrap;
        }

        .song-unlike-btn:hover {
            color: #ffffff;
            transform: translateY(-1px) scale(1.06);
            opacity: 0.92;
        }

        .song-unlike-btn:active {
            transform: scale(0.97);
        }

        .song-unlike-btn i {
            font-size: 15px;
        }

        .favorite-song-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .favorite-song-actions-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .favorite-action-round {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: linear-gradient(90deg, #00d1ff 0%, #31e7ff 100%);
            color: #042431;
            font-weight: 800;
            cursor: pointer;
            transition: filter 0.18s ease, transform 0.18s ease;
        }

        .favorite-action-round:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .favorite-menu-wrap {
            position: relative;
        }

        .favorite-menu-trigger {
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

        .favorite-menu-trigger:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 209, 255, 0.45);
            background: rgba(20, 26, 40, 0.98);
        }

        .favorite-action-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            min-width: 260px;
            background: #0f1220;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.35);
            padding: 8px;
            display: none;
            z-index: 30;
        }

        .favorite-action-menu.open {
            display: block;
        }

        .favorite-action-menu button {
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

        .favorite-action-menu button:hover {
            background: rgba(0, 209, 255, 0.08);
            color: #8be8ff;
        }

        .favorite-action-menu .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 6px 4px;
        }

        .favorite-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
        }

        .favorite-modal.open {
            display: flex;
        }

        .favorite-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(2, 5, 12, 0.72);
            backdrop-filter: blur(8px);
        }

        .favorite-modal-dialog {
            position: relative;
            width: min(100%, 420px);
            background: #131827;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.45);
        }

        .favorite-modal-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .favorite-modal-desc {
            color: #9aa3b2;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .favorite-copy-select {
            width: 100%;
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 10px 12px;
            border-radius: 10px;
            outline: none;
        }

        .favorite-modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .favorite-modal-actions .btn {
            flex: 1;
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

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #edf2ff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
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

        .artist-unlike-btn {
            width: 100%;
            border: 1px solid rgba(255, 68, 102, 0.28);
            background: rgba(255, 68, 102, 0.12);
            color: #ff9bab;
            border-radius: 12px;
            padding: 10px 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .artist-unlike-btn:hover {
            background: rgba(255, 68, 102, 0.2);
            border-color: rgba(255, 68, 102, 0.5);
            color: #ffd0db;
            transform: translateY(-1px);
        }

        .artist-unlike-btn:active {
            transform: translateY(0);
        }

        .artist-unlike-btn::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 50%;
            bottom: calc(100% + 8px);
            transform: translateX(-50%);
            background: #1b2030;
            color: #dbe2f0;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.08);
            font-size: 12px;
            line-height: 1;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease;
            z-index: 20;
        }

        .artist-unlike-btn:hover::after,
        .artist-unlike-btn:focus-visible::after {
            opacity: 1;
        }

        .artist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .artist-card {
            background: #0f1220;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .artist-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 209, 255, 0.35);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.35);
        }

        .artist-cover-link {
            display: block;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            line-height: 0;
            border: 2px solid rgba(255, 255, 255, 0.08);
            transition: border-color 0.2s ease;
        }

        .artist-card:hover .artist-cover-link {
            border-color: rgba(0, 209, 255, 0.45);
        }

        .artist-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.25s ease;
        }

        .artist-cover-link:hover .artist-cover {
            transform: scale(1.04);
        }

        .artist-name {
            font-weight: 700;
            margin-bottom: 4px;
            text-align: center;
        }

        .artist-meta {
            text-align: center;
        }

        .artist-card .artist-unlike-btn {
            position: relative;
        }

        .empty-box {
            color: #8f95af;
            font-size: 14px;
            padding: 10px 0;
        }
    </style>

    <div class="library-header">
        <h1 class="library-title">{{ $title }}</h1>
        <p class="library-subtitle">{{ $subtitle }}</p>
    </div>

    <div class="library-panel">
        @if($section === 'songs')
            <div class="favorite-song-actions">
                <div class="favorite-song-actions-left">
                    <button id="favoritePlayAllBtn" class="favorite-action-round" type="button" title="Phát danh sách yêu thích" aria-label="Phát danh sách yêu thích">
                        <i class="fas fa-play"></i>
                    </button>
                    <button id="favoriteRandomBtn" class="favorite-action-round" type="button" title="Phát ngẫu nhiên" aria-label="Phát ngẫu nhiên">
                        <i class="fas fa-random"></i>
                    </button>
                </div>

                <div class="favorite-menu-wrap">
                    <button id="favoriteMenuBtn" class="favorite-menu-trigger" type="button" title="Tùy chọn bài hát yêu thích" aria-label="Tùy chọn bài hát yêu thích">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>

                    <div id="favoriteActionMenu" class="favorite-action-menu" aria-hidden="true">
                        <button type="button" data-action="add-all-to-queue"><i class="fas fa-list"></i><span>Thêm tất cả vào danh sách chờ</span></button>
                        <button type="button" data-action="copy-all-to-playlist"><i class="fas fa-clone"></i><span>Thêm toàn bộ vào playlist</span></button>
                        <div class="menu-divider"></div>
                        <button type="button" data-action="refresh"><i class="fas fa-rotate-right"></i><span>Tải lại danh sách</span></button>
                    </div>
                </div>
            </div>

            <div id="likedSongsEmpty" class="empty-box" style="{{ $likedSongs->isEmpty() ? '' : 'display:none;' }}">Bạn chưa có bài hát yêu thích nào.</div>
            <table class="song-table" id="likedSongsTable" style="{{ $likedSongs->isEmpty() ? 'display:none;' : '' }}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bài hát</th>
                            <th>Playlist</th>
                            <th style="text-align: right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($likedSongs as $index => $song)
                            <tr class="song-item-row" data-liked-song-row="{{ $song->song_id }}"
                                data-id="{{ $song->song_id }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                                data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}"
                                data-artist-id="{{ $song->artist_id ?? '' }}"
                                data-album-id="{{ $song->album_id ?? '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #06131a; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-play" style="font-size: 11px;"></i>
                                        </button>
                                        <a href="{{ route('song.details', ['id' => $song->song_id]) }}" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                                            <img src="{{ $song->image_url ?: asset('image/default-cover.jpg') }}" class="song-thumb" alt="{{ $song->title }}">
                                        </a>
                                        <div>
                                            <div class="song-title">{{ $song->title }}</div>
                                            <div class="song-artist">{{ $song->artist->name ?? 'Unknown Artist' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <td class="song-meta" style="text-align: right;">
                                    <button type="button" class="song-unlike-btn btn-like-song liked" data-id="{{ $song->song_id }}" data-remove-on-unlike="1" title="Bỏ bài hát khỏi yêu thích">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            <div id="favoriteCopyModal" class="favorite-modal" aria-hidden="true">
                <div class="favorite-modal-backdrop" data-favorite-modal-close="1"></div>
                <div class="favorite-modal-dialog">
                    <div class="favorite-modal-title">Thêm toàn bộ bài hát yêu thích sang playlist</div>
                    <div class="favorite-modal-desc">Chọn playlist đích để thêm tất cả bài hát yêu thích hiện có.</div>

                    <form id="favoriteCopyForm">
                        <select id="favoriteCopyTarget" class="favorite-copy-select" required>
                            <option value="">-- Chọn playlist đích --</option>
                        </select>

                        <div class="favorite-modal-actions">
                            <button class="btn btn-danger-ghost" type="button" id="favoriteDeletePlaylistBtn">Xóa playlist đã chọn</button>
                            <button class="btn btn-secondary" type="button" data-favorite-modal-close="1">Hủy</button>
                            <button class="btn btn-primary" type="submit" id="favoriteCopySubmitBtn">Thêm toàn bộ</button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($section === 'artists')
            <div id="likedArtistsEmpty" class="empty-box" style="{{ $likedArtists->isEmpty() ? '' : 'display:none;' }}">Bạn chưa có nghệ sĩ yêu thích nào.</div>
            <div class="artist-grid" id="likedArtistsGrid" style="{{ $likedArtists->isEmpty() ? 'display:none;' : '' }}">
                @foreach($likedArtists as $artist)
                    <div class="artist-card" data-artist-card="{{ $artist->artist_id }}">
                        <a class="artist-cover-link" href="{{ route('artists.show', ['id' => $artist->artist_id]) }}" data-no-ajax="false">
                            <img src="{{ $artist->image_url ?: asset('image/default_artist.png') }}" class="artist-cover" alt="{{ $artist->name }}" onerror="this.src='{{ asset('image/default_artist.png') }}'">
                        </a>

                        <div class="artist-meta">
                            <div class="artist-name">{{ $artist->name }}</div>
                            <div class="song-meta">Đã thêm vào danh sách yêu thích.</div>
                        </div>

                        <button type="button" class="artist-unlike-btn btn-like-artist liked" data-id="{{ $artist->artist_id }}" data-remove-on-unlike="1" data-tooltip="Bỏ nghệ sĩ khỏi yêu thích" title="Bỏ nghệ sĩ khỏi yêu thích">
                            <i class="fas fa-heart-broken"></i>
                            <span>Bỏ yêu thích</span>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div id="guestHistoryEmpty" class="empty-box" style="display: none;">Chưa có lịch sử nghe nào được ghi nhận.</div>
            <table class="song-table" id="historyTable" style="display: none;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bài hát</th>
                        <th class="history-time-col">Đã nghe lúc</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    @foreach($historyItems as $index => $item)
                        <tr class="song-item-row"
                            data-id="{{ $item->song_id }}"
                            data-title="{{ $item->title }}"
                            data-artist="{{ $item->artist_name ?? 'Unknown Artist' }}"
                            data-src="{{ route('song.stream', ['id' => $item->song_id]) }}"
                            data-cover="{{ $item->image_url ?: asset('image/default-cover.jpg') }}"
                            data-artist-id="{{ $item->artist_id ?? '' }}"
                            data-album-id="{{ $item->album_id ?? '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #06131a; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-play" style="font-size: 11px;"></i>
                                    </button>
                                    <a href="{{ route('song.details', ['id' => $item->song_id]) }}" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                                        <img src="{{ $item->image_url ?: asset('image/default-cover.jpg') }}" class="song-thumb" alt="{{ $item->title }}">
                                    </a>
                                    <div>
                                        <div class="song-title">{{ $item->title }}</div>
                                        <div class="song-artist">{{ $item->artist_name ?? 'Unknown Artist' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="song-meta history-time-col">{{ $item->listened_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($section === 'songs')
        <script>
            (function () {
                const playAllBtn = document.getElementById('favoritePlayAllBtn');
                const randomBtn = document.getElementById('favoriteRandomBtn');
                const menuBtn = document.getElementById('favoriteMenuBtn');
                const actionMenu = document.getElementById('favoriteActionMenu');
                const copyModal = document.getElementById('favoriteCopyModal');
                const copyForm = document.getElementById('favoriteCopyForm');
                const copyTarget = document.getElementById('favoriteCopyTarget');
                const copySubmitBtn = document.getElementById('favoriteCopySubmitBtn');
                const deletePlaylistBtn = document.getElementById('favoriteDeletePlaylistBtn');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const addSongUrl = @json(route('playlist.add-song'));
                const destroyPlaylistUrlTemplate = @json(route('playlist.destroy', ['id' => '__PLAYLIST_ID__']));

                const toast = (message, type = 'success') => {
                    if (typeof window.showToast === 'function') {
                        window.showToast(message, type);
                        return;
                    }
                    alert(message);
                };

                const getFavoriteRows = () => Array.from(document.querySelectorAll('[data-liked-song-row].song-item-row'));

                const getSongFromRow = (row) => {
                    if (!row) return null;
                    return {
                        id: row.dataset.id,
                        title: row.dataset.title,
                        artist: row.dataset.artist,
                        src: row.dataset.src,
                        cover: row.dataset.cover,
                    };
                };

                const playSongData = (song) => {
                    if (!song || !song.id || !song.src) {
                        toast('Không thể phát bài hát do thiếu dữ liệu.', 'error');
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
                };

                const closeMenu = () => {
                    if (!actionMenu) return;
                    actionMenu.classList.remove('open');
                    actionMenu.setAttribute('aria-hidden', 'true');
                };

                const openMenu = () => {
                    if (!actionMenu) return;
                    actionMenu.classList.add('open');
                    actionMenu.setAttribute('aria-hidden', 'false');
                };

                const openCopyModal = () => {
                    if (!copyModal || !copyTarget) return;
                    const playlists = Array.isArray(window.playerPlaylists) ? window.playerPlaylists : [];

                    if (!playlists.length) {
                        toast('Bạn chưa có playlist nào. Hãy tạo playlist trước.', 'warning');
                        return;
                    }

                    copyTarget.innerHTML = '<option value="">-- Chọn playlist đích --</option>' + playlists.map((playlist) => {
                        return `<option value="${playlist.playlist_id}">${playlist.name}</option>`;
                    }).join('');

                    copyModal.classList.add('open');
                    copyModal.setAttribute('aria-hidden', 'false');
                    copyTarget.focus();
                };

                const syncCopyTargetOptions = () => {
                    if (!copyTarget) return;

                    const playlists = Array.isArray(window.playerPlaylists) ? window.playerPlaylists : [];
                    copyTarget.innerHTML = '<option value="">-- Chọn playlist đích --</option>' + playlists.map((playlist) => {
                        return `<option value="${playlist.playlist_id}">${playlist.name}</option>`;
                    }).join('');

                    if (!playlists.length) {
                        closeCopyModal();
                        toast('Bạn chưa có playlist nào.', 'warning');
                    }
                };

                const closeCopyModal = () => {
                    if (!copyModal) return;
                    copyModal.classList.remove('open');
                    copyModal.setAttribute('aria-hidden', 'true');
                };

                if (playAllBtn) {
                    playAllBtn.addEventListener('click', () => {
                        const rows = getFavoriteRows();
                        if (!rows.length) {
                            toast('Danh sách yêu thích chưa có bài hát.', 'warning');
                            return;
                        }
                        playSongData(getSongFromRow(rows[0]));
                    });
                }

                if (randomBtn) {
                    randomBtn.addEventListener('click', () => {
                        const rows = getFavoriteRows();
                        if (!rows.length) {
                            toast('Danh sách yêu thích chưa có bài hát.', 'warning');
                            return;
                        }

                        const randomRow = rows[Math.floor(Math.random() * rows.length)];
                        const shuffleBtnUI = document.getElementById('shuffleBtn');
                        if (shuffleBtnUI) {
                            shuffleBtnUI.classList.add('active');
                        }
                        playSongData(getSongFromRow(randomRow));
                    });
                }

                if (menuBtn && actionMenu) {
                    menuBtn.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        if (actionMenu.classList.contains('open')) {
                            closeMenu();
                        } else {
                            openMenu();
                        }
                    });

                    actionMenu.addEventListener('click', async (event) => {
                        const button = event.target.closest('[data-action]');
                        if (!button) return;

                        const action = button.dataset.action;
                        closeMenu();

                        if (action === 'refresh') {
                            if (typeof window.partialNavigate === 'function') {
                                await window.partialNavigate(window.location.href, { fromPopState: true });
                            } else {
                                window.location.reload();
                            }
                            return;
                        }

                        if (action === 'add-all-to-queue') {
                            const rows = getFavoriteRows();
                            if (!rows.length) {
                                toast('Danh sách yêu thích chưa có bài hát.', 'warning');
                                return;
                            }

                            let addedCount = 0;
                            rows.forEach((row) => {
                                const song = getSongFromRow(row);
                                if (song && song.id && typeof window.addToQueue === 'function') {
                                    window.addToQueue(song.id);
                                    addedCount++;
                                }
                            });

                            toast(`Đã thêm ${addedCount} bài hát vào danh sách chờ.`, 'success');
                            return;
                        }

                        if (action === 'copy-all-to-playlist') {
                            openCopyModal();
                        }
                    });
                }

                document.addEventListener('click', (event) => {
                    if (actionMenu && menuBtn && !menuBtn.contains(event.target) && !actionMenu.contains(event.target)) {
                        closeMenu();
                    }
                });

                if (copyModal) {
                    copyModal.addEventListener('click', (event) => {
                        if (event.target && event.target.dataset && event.target.dataset.favoriteModalClose === '1') {
                            closeCopyModal();
                        }
                    });
                }

                if (copyForm && copyTarget) {
                    copyForm.addEventListener('submit', async (event) => {
                        event.preventDefault();

                        const targetPlaylistId = copyTarget.value;
                        if (!targetPlaylistId) {
                            toast('Vui lòng chọn playlist đích.', 'warning');
                            return;
                        }

                        const songIds = [...new Set(getFavoriteRows().map((row) => row.dataset.id).filter(Boolean))];
                        if (!songIds.length) {
                            toast('Danh sách yêu thích chưa có bài hát.', 'warning');
                            return;
                        }

                        if (copySubmitBtn) {
                            copySubmitBtn.disabled = true;
                            copySubmitBtn.textContent = 'Đang thêm...';
                        }

                        let successCount = 0;

                        try {
                            for (const songId of songIds) {
                                const response = await fetch(addSongUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    body: JSON.stringify({
                                        playlist_id: Number(targetPlaylistId),
                                        song_id: Number(songId),
                                    }),
                                });

                                const data = await response.json();
                                if (response.ok && data.status === 'success') {
                                    successCount++;
                                }
                            }

                            closeCopyModal();
                            if (successCount === 0) {
                                toast('Không có bài hát nào được thêm (có thể đã tồn tại trong playlist).', 'warning');
                            } else {
                                toast(`Đã thêm ${successCount}/${songIds.length} bài hát vào playlist.`, 'success');
                            }
                        } catch (error) {
                            toast('Lỗi kết nối, vui lòng thử lại.', 'error');
                        } finally {
                            if (copySubmitBtn) {
                                copySubmitBtn.disabled = false;
                                copySubmitBtn.textContent = 'Thêm toàn bộ';
                            }
                        }
                    });
                }

                if (deletePlaylistBtn && copyTarget) {
                    deletePlaylistBtn.addEventListener('click', async () => {
                        const selectedPlaylistId = copyTarget.value;
                        if (!selectedPlaylistId) {
                            toast('Vui lòng chọn playlist cần xóa.', 'warning');
                            return;
                        }

                        const selectedPlaylistName = copyTarget.options[copyTarget.selectedIndex]?.textContent || 'playlist đã chọn';
                        const confirmed = confirm(`Bạn có chắc muốn xóa \"${selectedPlaylistName}\"? Hành động này không thể hoàn tác.`);
                        if (!confirmed) return;

                        deletePlaylistBtn.disabled = true;
                        deletePlaylistBtn.textContent = 'Đang xóa...';

                        try {
                            const destroyUrl = destroyPlaylistUrlTemplate.replace('__PLAYLIST_ID__', selectedPlaylistId);
                            const response = await fetch(destroyUrl, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                            });

                            const data = await response.json();
                            if (!response.ok || data.status !== 'success') {
                                toast(data.message || 'Không thể xóa playlist đã chọn.', 'error');
                                return;
                            }

                            window.playerPlaylists = (Array.isArray(window.playerPlaylists) ? window.playerPlaylists : []).filter((playlist) => {
                                return String(playlist.playlist_id) !== String(selectedPlaylistId);
                            });

                            syncCopyTargetOptions();
                            if (typeof window.renderPlayerPlaylistList === 'function') {
                                window.renderPlayerPlaylistList();
                            }

                            toast(data.message || 'Đã xóa playlist.', 'success');
                        } catch (error) {
                            toast('Lỗi kết nối, vui lòng thử lại.', 'error');
                        } finally {
                            deletePlaylistBtn.disabled = false;
                            deletePlaylistBtn.textContent = 'Xóa playlist đã chọn';
                        }
                    });
                }
            })();
        </script>
    @endif

    @if($section === 'history')
        <script>
            (function () {
                const isGuestHistory = @json($isGuestHistory ?? false);
                const table = document.getElementById('historyTable');
                const tbody = document.getElementById('historyTableBody');
                const empty = document.getElementById('guestHistoryEmpty');

                if (!table || !tbody || !empty) return;

                if (!isGuestHistory) {
                    const hasRows = tbody.children.length > 0;
                    table.style.display = hasRows ? 'table' : 'none';
                    empty.style.display = hasRows ? 'none' : 'block';
                    return;
                }

                const guestHistory = JSON.parse(localStorage.getItem('guest_listen_history') || '[]');
                if (!Array.isArray(guestHistory) || guestHistory.length === 0) {
                    table.style.display = 'none';
                    empty.style.display = 'block';
                    return;
                }

                tbody.innerHTML = guestHistory.map((item, index) => {
                    const listenedAt = item.listened_at ? new Date(item.listened_at).toLocaleString('vi-VN') : '--';
                    const cover = item.cover || '{{ asset('image/default-cover.jpg') }}';
                    const artist = item.artist || 'Unknown Artist';
                    const src = item.src || '';

                    return `
                        <tr class="song-item-row"
                            data-id="${item.id || ''}"
                            data-title="${item.title || ''}"
                            data-artist="${artist}"
                            data-src="${src}"
                            data-cover="${cover}"
                            data-artist-id=""
                            data-album-id="">
                            <td>${index + 1}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #06131a; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-play" style="font-size: 11px;"></i>
                                    </button>
                                    <a href="/song/${item.id || 0}/chitiet" style="display: inline-flex; line-height: 0;" data-no-ajax="false">
                                        <img src="${cover}" class="song-thumb" alt="${item.title || ''}">
                                    </a>
                                    <div>
                                        <div class="song-title">${item.title || ''}</div>
                                        <div class="song-artist">${artist}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="song-meta history-time-col">${listenedAt}</td>
                        </tr>
                    `;
                }).join('');

                table.style.display = 'table';
                empty.style.display = 'none';
            })();
        </script>
    @endif
</x-client-layout>
