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
        }

        .artist-name {
            font-weight: 700;
            margin-bottom: 6px;
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

    @if($section !== 'history')
        <div class="library-tabs">
            <a class="library-tab {{ $section === 'songs' ? 'active' : '' }}" href="{{ route('library.songs') }}">
                <i class="fas fa-heart"></i> Bài hát yêu thích
            </a>
            <a class="library-tab {{ $section === 'artists' ? 'active' : '' }}" href="{{ route('library.artists') }}">
                <i class="fas fa-microphone-alt"></i> Nghệ sĩ yêu thích
            </a>
        </div>
    @endif

    <div class="library-panel">
        @if($section === 'songs')
            @if($likedSongs->isEmpty())
                <div class="empty-box">Bạn chưa có bài hát yêu thích nào.</div>
            @else
                <table class="song-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bài hát</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($likedSongs as $index => $song)
                            <tr class="song-item-row"
                                data-id="{{ $song->song_id }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                data-src="{{ asset('audio/' . ltrim($song->audio_file, '/')) }}"
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
                                <td class="song-meta"><i class="fas fa-heart" style="color:#ff007a;"></i> Đã yêu thích</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @elseif($section === 'artists')
            @if($likedArtists->isEmpty())
                <div class="empty-box">Bạn chưa có nghệ sĩ yêu thích nào.</div>
            @else
                <div class="artist-grid">
                    @foreach($likedArtists as $artist)
                        <div class="artist-card">
                            <div class="artist-name">{{ $artist->name }}</div>
                            <div class="song-meta">Đã thêm vào danh sách yêu thích.</div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div id="guestHistoryEmpty" class="empty-box" style="display: none;">Chưa có lịch sử nghe nào được ghi nhận.</div>
            <table class="song-table" id="historyTable" style="display: none;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bài hát</th>
                        <th>Đã nghe lúc</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    @foreach($historyItems as $index => $item)
                        <tr class="song-item-row"
                            data-id="{{ $item->song_id }}"
                            data-title="{{ $item->title }}"
                            data-artist="{{ $item->artist_name ?? 'Unknown Artist' }}"
                            data-src="{{ asset('audio/' . ltrim($item->audio_file, '/')) }}"
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
                            <td class="song-meta">{{ $item->listened_at }}</td>
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
                            <td class="song-meta">${listenedAt}</td>
                        </tr>
                    `;
                }).join('');

                table.style.display = 'table';
                empty.style.display = 'none';
            })();
        </script>
    @endif
</x-client-layout>
