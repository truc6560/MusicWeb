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

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Nghệ sĩ <span style="color: red;">*</span></label>
                <select name="artist_id" required style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
                    <option value="">-- Chọn nghệ sĩ --</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->artist_id }}" {{ old('artist_id') == $artist->artist_id ? 'selected' : '' }}>{{ $artist->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Album</label>
                <select name="album_id" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
                    <option value="">-- Chưa có album --</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->album_id }}" {{ old('album_id') == $album->album_id ? 'selected' : '' }}>{{ $album->title }}</option>
                    @endforeach
                </select>
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
