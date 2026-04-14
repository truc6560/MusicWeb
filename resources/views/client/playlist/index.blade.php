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

        .panel {
            background: #161822;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
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
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .playlist-item {
            padding: 12px;
            border-radius: 12px;
            background: #111424;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, border-color 0.2s ease;
            display: block;
        }

        .playlist-item:hover {
            transform: translateY(-3px);
            border-color: rgba(0, 209, 255, 0.35);
        }

        .playlist-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .playlist-item-name {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .playlist-item-count {
            color: #a0a0a0;
            font-size: 13px;
        }

        .empty-box {
            color: #8f95af;
            font-size: 14px;
            padding: 10px 0;
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

    </style>

    <h1 class="playlist-page-title">Playlist của tôi</h1>
    <p class="playlist-page-subtitle">Danh sách playlist của bạn. Nhấn vào từng playlist để xem chi tiết.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="panel">
        <h3>Tạo Playlist mới</h3>
        <form class="playlist-form" method="POST" action="{{ route('playlist.store') }}">
            @csrf
            <input type="text" name="name" placeholder="Ví dụ: Chill buổi tối" required>
            <button type="submit">Tạo</button>
        </form>
    </div>

    <div class="panel">
        <h3>Danh sách Playlist hiện có</h3>
        <div class="playlist-list">
            @forelse($playlists as $playlist)
                <a class="playlist-item" href="{{ route('playlist.show', ['id' => $playlist->playlist_id]) }}" data-no-ajax="false">
                    <img src="{{ $playlist->cover_url }}" class="playlist-cover" alt="{{ $playlist->name }}">
                    <div class="playlist-item-name">{{ $playlist->name }}</div>
                    <div class="playlist-item-count">{{ $playlist->songs_count }} bài hát</div>
                </a>
            @empty
                <div class="empty-box">Chưa có playlist nào.</div>
            @endforelse
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

    </script>
</x-client-layout>
