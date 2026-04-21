<x-admin-layout title="Quản lý Bài hát">
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: none; margin-bottom: 0;">
        <h2 style="color: #00d1ff; text-align: center; width: 100%;"><i class="fas fa-music"></i> Thêm Bài Hát Mới</h2>
    </div>

    @if ($errors->any())
        <div style="background: rgba(255, 68, 102, 0.2); color: #ff4466; border: 1px solid #ff4466; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            Vui lòng kiểm tra lại thông tin nhập vào!
        </div>
    @endif

    <form action="{{ route('admin.songs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tên bài hát <span style="color: red;">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div style="position: relative;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Nghệ sĩ <span style="color: red;">*</span></label>
                @php
                    $selectedArtist = $artists->firstWhere('artist_id', (int) old('artist_id'));
                @endphp
                <input type="text" id="artist_search" value="{{ $selectedArtist?->name ?? '' }}" placeholder="Nhập để tìm nghệ sĩ..." autocomplete="off" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
                <input type="hidden" name="artist_id" id="artist_id" value="{{ old('artist_id') }}" required>
                <div id="artist_results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 6px; background: #1a1c26; border: 1px solid #2d2f3b; border-radius: 8px; max-height: 220px; overflow-y: auto; z-index: 20;"></div>
            </div>

            <div style="position: relative;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Album</label>
                @php
                    $selectedAlbum = $albums->firstWhere('album_id', (int) old('album_id'));
                @endphp
                <input type="text" id="album_search" value="{{ $selectedAlbum?->title ?? '' }}" placeholder="Nhập để tìm album..." autocomplete="off" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
                <input type="hidden" name="album_id" id="album_id" value="{{ old('album_id') }}">
                <div id="album_results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 6px; background: #1a1c26; border: 1px solid #2d2f3b; border-radius: 8px; max-height: 220px; overflow-y: auto; z-index: 20;"></div>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Thể loại</label>
                <input type="text" name="genres" value="{{ old('genres') }}" placeholder="Ví dụ: V-Pop, Hip Hop" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Thời lượng</label>
                <input type="text" name="duration" value="{{ old('duration') }}" placeholder="Ví dụ: 03:21" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Lượt nghe</label>
                <input type="number" min="0" name="plays" value="{{ old('plays', 0) }}" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Ngày phát hành</label>
                <input type="date" name="release_date" value="{{ old('release_date') }}" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Ảnh bìa URL</label>
                <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">File âm thanh URL</label>
                <input type="text" name="audio_file" value="{{ old('audio_file') }}" placeholder="https://example.com/audio.mp3" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Lyrics</label>
                <textarea name="lyrics" style="width: 100%; min-height: 140px; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none; resize: vertical;">{{ old('lyrics') }}</textarea>
                <div style="margin-top: 8px; color: #8f95af; font-size: 12px; line-height: 1.6;">Nếu bài hát có file LRC thì tải lên bên dưới để chạy theo nhạc. Không có LRC thì nhập lời như cũ.</div>
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tệp LRC (không bắt buộc)</label>
                <input type="file" name="lrc_file" accept=".lrc,.txt" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>
        </div>

        <button type="submit" class="btn-add" style="margin-top: 20px; width: 100%; border: none; cursor: pointer; font-size: 1rem; text-align: center; display: block;">
            Lưu Bài Hát
        </button>

        <a href="{{ route('admin.songs.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #666; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </form>
</div>
</x-admin-layout>

<script>
    (function () {
        const artists = @json($artists->map(function ($artist) { return ['id' => (string) $artist->artist_id, 'name' => $artist->name]; })->values());
        const albums = @json($albums->map(function ($album) { return ['id' => (string) $album->album_id, 'name' => $album->title]; })->values());

        function setupSearch(config) {
            const input = document.getElementById(config.inputId);
            const hidden = document.getElementById(config.hiddenId);
            const results = document.getElementById(config.resultsId);
            const items = config.items;

            function renderList(filtered) {
                if (!filtered.length) {
                    results.innerHTML = '<div style="padding: 10px 12px; color: #8f95af; font-size: 13px;">Không tìm thấy kết quả phù hợp.</div>';
                    results.style.display = 'block';
                    return;
                }

                results.innerHTML = filtered.slice(0, 8).map(function (item) {
                    return '<button type="button" data-id="' + item.id + '" data-name="' + item.name.replace(/"/g, '&quot;') + '" style="width: 100%; text-align: left; background: transparent; border: none; padding: 10px 12px; color: #fff; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05);">' + item.name + '</button>';
                }).join('');
                results.style.display = 'block';
            }

            function syncHiddenByExactMatch() {
                const value = input.value.trim().toLowerCase();
                if (!value) {
                    hidden.value = '';
                    return;
                }
                const matched = items.find(function (item) {
                    return item.name.toLowerCase() === value;
                });
                hidden.value = matched ? matched.id : '';
            }

            input.addEventListener('input', function () {
                const keyword = input.value.trim().toLowerCase();
                if (!keyword) {
                    hidden.value = '';
                    results.style.display = 'none';
                    return;
                }
                hidden.value = '';
                const filtered = items.filter(function (item) {
                    return item.name.toLowerCase().includes(keyword);
                });
                renderList(filtered);
            });

            input.addEventListener('focus', function () {
                const keyword = input.value.trim().toLowerCase();
                const filtered = keyword
                    ? items.filter(function (item) { return item.name.toLowerCase().includes(keyword); })
                    : items.slice(0, 8);
                renderList(filtered);
            });

            input.addEventListener('blur', function () {
                setTimeout(function () {
                    syncHiddenByExactMatch();
                    results.style.display = 'none';
                }, 120);
            });

            results.addEventListener('click', function (event) {
                const target = event.target.closest('button[data-id]');
                if (!target) {
                    return;
                }
                input.value = target.getAttribute('data-name');
                hidden.value = target.getAttribute('data-id');
                results.style.display = 'none';
            });
        }

        setupSearch({
            inputId: 'artist_search',
            hiddenId: 'artist_id',
            resultsId: 'artist_results',
            items: artists,
        });

        setupSearch({
            inputId: 'album_search',
            hiddenId: 'album_id',
            resultsId: 'album_results',
            items: albums,
        });
    })();
</script>
