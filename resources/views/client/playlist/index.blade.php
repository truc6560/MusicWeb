<x-client-layout>
    <style>
        .playlist-page-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .playlist-page-subtitle {
            color: #a0a0a0;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .playlist-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 20px;
        }

        .panel {
            background: #161822;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 20px;
        }

        .panel h3 {
            font-size: 18px;
            margin-bottom: 14px;
        }

        .playlist-form {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .playlist-form input {
            flex: 1;
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            padding: 10px 12px;
            border-radius: 8px;
            outline: none;
        }

        .playlist-form button {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(90deg, #00d1ff 0%, #bd00ff 100%);
            color: #fff;
        }

        .playlist-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 240px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .playlist-item {
            padding: 12px;
            border-radius: 10px;
            background: #111424;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .playlist-item-name {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .playlist-item-count {
            color: #a0a0a0;
            font-size: 13px;
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

        .playlist-action {
            display: flex;
            gap: 8px;
        }

        .playlist-action select {
            background: #0f1220;
            border: 1px solid #2b2f44;
            color: #fff;
            border-radius: 6px;
            padding: 6px 8px;
            min-width: 140px;
        }

        .playlist-action button {
            border: none;
            border-radius: 6px;
            background: #00d1ff;
            color: #06131a;
            font-weight: 700;
            padding: 6px 10px;
            cursor: pointer;
        }

        .empty-box {
            color: #8f95af;
            font-size: 14px;
            padding: 10px 0;
        }

        .stack {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .playlist-song-list {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .playlist-song-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            background: #0f1220;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 8px 10px;
        }

        .playlist-song-row .meta {
            overflow: hidden;
        }

        .playlist-song-row .meta strong,
        .playlist-song-row .meta span {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .playlist-song-row .meta span {
            color: #a0a0a0;
            font-size: 13px;
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

        .alert-error {
            background: rgba(255, 78, 110, 0.12);
            border: 1px solid rgba(255, 78, 110, 0.35);
            color: #ff9bab;
        }

        @media (max-width: 1100px) {
            .playlist-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <h1 class="playlist-page-title">Playlist của tôi</h1>
    <p class="playlist-page-subtitle">Tạo playlist mới, thêm bài hát yêu thích vào playlist và phát trực tiếp từ danh sách.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="playlist-grid">
        <div class="panel">
            <h3>Bài hát đã yêu thích</h3>

            @if($likedSongs->isEmpty())
                <div class="empty-box">Bạn chưa có bài hát yêu thích nào.</div>
            @else
                <table class="song-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bài hát</th>
                            <th>Thêm vào Playlist</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($likedSongs as $idx => $song)
                            @php
                                $cover = $song->image_url ?: asset('image/default-cover.jpg');
                                $audioPath = asset('audio/' . ltrim($song->audio_file, '/'));
                            @endphp
                            <tr class="song-item-row"
                                data-id="{{ $song->song_id }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                data-src="{{ $audioPath }}"
                                data-cover="{{ $cover }}">
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="{{ $cover }}" class="song-thumb" alt="{{ $song->title }}">
                                        <div>
                                            <div class="song-title">{{ $song->title }}</div>
                                            <div class="song-artist">{{ $song->artist->name ?? 'Unknown Artist' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="playlist-action" onclick="event.stopPropagation()">
                                        <select class="playlist-select" data-song-id="{{ $song->song_id }}">
                                            @foreach($playlists as $playlist)
                                                <option value="{{ $playlist->playlist_id }}">{{ $playlist->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn-add-to-playlist" data-song-id="{{ $song->song_id }}">Thêm</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="stack">
            <div class="panel">
                <h3>Tạo Playlist mới</h3>
                <form class="playlist-form" method="POST" action="{{ route('playlist.store') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Ví dụ: Chill buổi tối" required>
                    <button type="submit">Tạo</button>
                </form>
                <div class="playlist-list">
                    @forelse($playlists as $playlist)
                        <div class="playlist-item">
                            <div class="playlist-item-name">{{ $playlist->name }}</div>
                            <div class="playlist-item-count">{{ $playlist->songs->count() }} bài hát</div>
                        </div>
                    @empty
                        <div class="empty-box">Chưa có playlist nào.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <h3>Bài hát trong playlist</h3>
                @forelse($playlists as $playlist)
                    <div style="margin-bottom: 12px;">
                        <div class="playlist-item-name">{{ $playlist->name }}</div>
                        @if($playlist->songs->isEmpty())
                            <div class="empty-box">Playlist này chưa có bài hát.</div>
                        @else
                            <div class="playlist-song-list">
                                @foreach($playlist->songs as $song)
                                    <div class="playlist-song-row song-item-row"
                                         data-id="{{ $song->song_id }}"
                                         data-title="{{ $song->title }}"
                                         data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                                         data-src="{{ asset('audio/' . ltrim($song->audio_file, '/')) }}"
                                         data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}">
                                        <div class="meta">
                                            <strong>{{ $song->title }}</strong>
                                            <span>{{ $song->artist->name ?? 'Unknown Artist' }}</span>
                                        </div>
                                        <i class="fas fa-play" style="color:#00d1ff"></i>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-box">Tạo playlist trước để thêm bài hát.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        const playlistCreateForm = document.querySelector('.playlist-form');

        if (playlistCreateForm) {
            playlistCreateForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const formData = new FormData(this);

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    if (response.ok) {
                        if (typeof window.partialNavigate === 'function') {
                            await window.partialNavigate(window.location.href, { fromPopState: true });
                        }
                    } else {
                        alert('Không thể tạo playlist. Vui lòng kiểm tra dữ liệu nhập.');
                    }
                } catch (error) {
                    alert('Lỗi kết nối, vui lòng thử lại.');
                }
            });
        }

        document.querySelectorAll('.btn-add-to-playlist').forEach((btn) => {
            btn.addEventListener('click', async function () {
                const songId = this.dataset.songId;
                const select = document.querySelector(`.playlist-select[data-song-id="${songId}"]`);

                if (!select || !select.value) {
                    alert('Vui lòng tạo playlist trước khi thêm bài hát.');
                    return;
                }

                try {
                    const response = await fetch("{{ route('playlist.add-song') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            playlist_id: select.value,
                            song_id: songId
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.status === 'success') {
                        if (typeof window.partialNavigate === 'function') {
                            await window.partialNavigate(window.location.href, { fromPopState: true });
                        }
                    } else {
                        alert(data.message || 'Không thể thêm bài hát vào playlist.');
                    }
                } catch (error) {
                    alert('Lỗi kết nối, vui lòng thử lại.');
                }
            });
        });
    </script>
</x-client-layout>
