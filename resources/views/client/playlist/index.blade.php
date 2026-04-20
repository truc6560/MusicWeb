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

        .panel-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .panel-header-row h3 {
            margin-bottom: 0;
        }

        .playlist-edit-toggle-btn {
            width: 38px;
            height: 38px;
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

        .playlist-edit-toggle-btn:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 209, 255, 0.45);
            background: rgba(20, 26, 40, 0.98);
        }

        .playlist-edit-toggle-btn.is-active {
            border-color: rgba(0, 209, 255, 0.55);
            box-shadow: 0 0 0 1px rgba(0, 209, 255, 0.2) inset;
            color: #8be8ff;
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

        .playlist-list-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .playlist-list-toolbar[hidden] {
            display: none !important;
        }

        .playlist-select-all-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #d6deef;
            font-size: 13px;
            font-weight: 600;
        }

        .playlist-select-all-wrap input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .playlist-delete-selected-btn {
            border: 1px solid rgba(255, 88, 114, 0.35);
            background: rgba(255, 88, 114, 0.14);
            color: #ffd9e0;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
        }

        .playlist-delete-selected-btn:hover:not(:disabled) {
            background: rgba(255, 88, 114, 0.2);
            border-color: rgba(255, 88, 114, 0.5);
            transform: translateY(-1px);
        }

        .playlist-delete-selected-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .playlist-item {
            padding: 12px;
            border-radius: 12px;
            background: #111424;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
        }

        .playlist-item:hover {
            transform: translateY(-3px);
            border-color: rgba(0, 209, 255, 0.35);
        }

        .playlist-item.is-selected {
            border-color: rgba(0, 209, 255, 0.55);
            box-shadow: 0 0 0 1px rgba(0, 209, 255, 0.2) inset;
        }

        .playlist-item-top {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .playlist-item-top[hidden] {
            display: none !important;
        }

        .playlist-item-select {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.12);
            cursor: pointer;
        }

        .playlist-item-select input {
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .playlist-item-link {
            text-decoration: none;
            color: inherit;
            display: block;
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
        <div class="panel-header-row">
            <h3>Danh sách Playlist hiện có</h3>
            @if($playlists->isNotEmpty())
                <button type="button" id="playlistEditToggleBtn" class="playlist-edit-toggle-btn" title="Chỉnh sửa playlist" aria-label="Chỉnh sửa playlist">
                    <i class="fas fa-pen"></i>
                </button>
            @endif
        </div>

        @if($playlists->isNotEmpty())
            <div class="playlist-list-toolbar" id="playlistListToolbar" hidden>
                <label class="playlist-select-all-wrap" for="playlistSelectAll">
                    <input type="checkbox" id="playlistSelectAll">
                    <span>Chọn tất cả</span>
                </label>

                <button type="button" id="playlistDeleteSelectedBtn" class="playlist-delete-selected-btn" disabled>
                    <i class="fas fa-trash-alt"></i>
                    <span>Xóa đã chọn</span>
                </button>
            </div>
        @endif

        <div class="playlist-list">
            @forelse($playlists as $playlist)
                <div class="playlist-item" data-playlist-item-id="{{ $playlist->playlist_id }}">
                    <div class="playlist-item-top" hidden>
                        <label class="playlist-item-select" title="Chọn playlist">
                            <input type="checkbox" class="playlist-select-checkbox" value="{{ $playlist->playlist_id }}">
                        </label>
                    </div>

                    <a class="playlist-item-link" href="{{ route('playlist.show', ['id' => $playlist->playlist_id]) }}" data-no-ajax="false">
                        <img src="{{ $playlist->cover_url }}" class="playlist-cover" alt="{{ $playlist->name }}">
                        <div class="playlist-item-name">{{ $playlist->name }}</div>
                        <div class="playlist-item-count">{{ $playlist->songs_count }} bài hát</div>
                    </a>
                </div>
            @empty
                <div class="empty-box">Chưa có playlist nào.</div>
            @endforelse
        </div>
    </div>

    <script>
        const playlistCreateForm = document.querySelector('.playlist-form');
        const playlistEditToggleBtn = document.getElementById('playlistEditToggleBtn');
        const playlistListToolbar = document.getElementById('playlistListToolbar');
        const playlistSelectAll = document.getElementById('playlistSelectAll');
        const playlistDeleteSelectedBtn = document.getElementById('playlistDeleteSelectedBtn');
        let playlistEditMode = false;

        const getPlaylistCheckboxes = () => Array.from(document.querySelectorAll('.playlist-select-checkbox'));
        const getSelectedPlaylistIds = () => getPlaylistCheckboxes()
            .filter((checkbox) => checkbox.checked)
            .map((checkbox) => checkbox.value);

        const clearPlaylistSelections = () => {
            getPlaylistCheckboxes().forEach((checkbox) => {
                checkbox.checked = false;
            });
            if (playlistSelectAll) {
                playlistSelectAll.checked = false;
                playlistSelectAll.indeterminate = false;
            }
        };

        const setPlaylistEditMode = (enabled) => {
            playlistEditMode = Boolean(enabled);

            if (playlistListToolbar) {
                playlistListToolbar.hidden = !playlistEditMode;
            }

            if (playlistEditToggleBtn) {
                playlistEditToggleBtn.classList.toggle('is-active', playlistEditMode);
                playlistEditToggleBtn.setAttribute('aria-pressed', playlistEditMode ? 'true' : 'false');
                playlistEditToggleBtn.title = playlistEditMode ? 'Thoát chỉnh sửa playlist' : 'Chỉnh sửa playlist';
            }

            document.querySelectorAll('.playlist-item-top').forEach((itemTop) => {
                itemTop.hidden = !playlistEditMode;
            });

            if (!playlistEditMode) {
                clearPlaylistSelections();
            }

            updatePlaylistSelectionState();
        };

        const updatePlaylistSelectionState = () => {
            const checkboxes = getPlaylistCheckboxes();
            if (!checkboxes.length) return;

            const selectedCount = getSelectedPlaylistIds().length;
            const allChecked = selectedCount > 0 && selectedCount === checkboxes.length;

            if (playlistSelectAll) {
                playlistSelectAll.checked = allChecked;
                playlistSelectAll.indeterminate = selectedCount > 0 && !allChecked;
            }

            if (playlistDeleteSelectedBtn) {
                playlistDeleteSelectedBtn.disabled = !playlistEditMode || selectedCount === 0;
            }

            checkboxes.forEach((checkbox) => {
                const item = checkbox.closest('[data-playlist-item-id]');
                if (!item) return;
                item.classList.toggle('is-selected', checkbox.checked);
            });
        };

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

        if (playlistSelectAll) {
            playlistSelectAll.addEventListener('change', () => {
                if (!playlistEditMode) return;
                getPlaylistCheckboxes().forEach((checkbox) => {
                    checkbox.checked = playlistSelectAll.checked;
                });
                updatePlaylistSelectionState();
            });
        }

        if (playlistEditToggleBtn) {
            playlistEditToggleBtn.addEventListener('click', () => {
                setPlaylistEditMode(!playlistEditMode);
            });
        }

        getPlaylistCheckboxes().forEach((checkbox) => {
            checkbox.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            checkbox.addEventListener('change', () => {
                updatePlaylistSelectionState();
            });

            const playlistItem = checkbox.closest('.playlist-item');
            const playlistLink = playlistItem ? playlistItem.querySelector('.playlist-item-link') : null;

            if (playlistLink) {
                playlistLink.addEventListener('click', (event) => {
                    if (!playlistEditMode) return;
                    event.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    updatePlaylistSelectionState();
                });
            }
        });

        if (playlistDeleteSelectedBtn) {
            playlistDeleteSelectedBtn.addEventListener('click', async () => {
                const selectedIds = getSelectedPlaylistIds();
                if (!selectedIds.length) {
                    alert('Vui lòng chọn ít nhất 1 playlist để xóa.');
                    return;
                }

                const confirmed = confirm(`Bạn có chắc muốn xóa ${selectedIds.length} playlist đã chọn? Hành động này không thể hoàn tác.`);
                if (!confirmed) return;

                playlistDeleteSelectedBtn.disabled = true;
                playlistDeleteSelectedBtn.innerHTML = '<span>Đang xóa...</span>';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const destroyUrlTemplate = @json(route('playlist.destroy', ['id' => '__PLAYLIST_ID__']));

                let successCount = 0;

                try {
                    for (const playlistId of selectedIds) {
                        const destroyUrl = destroyUrlTemplate.replace('__PLAYLIST_ID__', String(playlistId));
                        const response = await fetch(destroyUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        let data = null;
                        try {
                            data = await response.json();
                        } catch (error) {
                            data = null;
                        }

                        if (response.ok && data.status === 'success') {
                            successCount++;
                        }
                    }

                    if (successCount > 0) {
                        if (typeof window.partialNavigate === 'function') {
                            await window.partialNavigate(window.location.href, { fromPopState: true });
                        } else {
                            window.location.reload();
                        }
                        return;
                    }

                    alert('Không thể xóa playlist đã chọn. Vui lòng thử lại.');
                } catch (error) {
                    alert('Lỗi kết nối, vui lòng thử lại.');
                } finally {
                    playlistDeleteSelectedBtn.disabled = false;
                    playlistDeleteSelectedBtn.innerHTML = '<i class="fas fa-trash-alt"></i><span>Xóa đã chọn</span>';
                    updatePlaylistSelectionState();
                }
            });
        }

        setPlaylistEditMode(false);
        updatePlaylistSelectionState();

    </script>
</x-client-layout>
