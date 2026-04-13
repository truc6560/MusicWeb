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
            border: 1px solid rgba(255, 68, 102, 0.28);
            background: rgba(255, 68, 102, 0.12);
            color: #ff9bab;
            border-radius: 999px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            white-space: nowrap;
        }

        .song-unlike-btn:hover {
            background: rgba(255, 68, 102, 0.2);
            border-color: rgba(255, 68, 102, 0.5);
            color: #ffd0db;
            transform: translateY(-1px);
        }

        .song-unlike-btn:active {
            transform: translateY(0);
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
            <div id="likedSongsEmpty" class="empty-box" style="{{ $likedSongs->isEmpty() ? '' : 'display:none;' }}">Bạn chưa có bài hát yêu thích nào.</div>
            <table class="song-table" id="likedSongsTable" style="{{ $likedSongs->isEmpty() ? 'display:none;' : '' }}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bài hát</th>
                            <th>Playlist</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($likedSongs as $index => $song)
                            <tr class="song-item-row" data-liked-song-row="{{ $song->song_id }}"
                                data-id="{{ $song->song_id }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                                data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}">
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
                                <td class="song-meta">
                                    <button type="button" class="song-unlike-btn btn-like-song liked" data-id="{{ $song->song_id }}" data-remove-on-unlike="1" title="Bỏ bài hát khỏi yêu thích">
                                        <i class="fas fa-heart-broken"></i>
                                        <span>Bỏ thích</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                            data-cover="{{ $item->image_url ?: asset('image/default-cover.jpg') }}">
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
                            data-cover="${cover}">
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
