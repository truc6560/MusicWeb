<x-admin-layout title="Quản lý Album">
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: none; margin-bottom: 0;">
        <h2 style="color: #00d1ff; text-align: center; width: 100%;"><i class="fas fa-compact-disc"></i> Cập nhật Album</h2>
    </div>

    @if ($errors->any())
        <div style="background: rgba(255, 68, 102, 0.2); color: #ff4466; border: 1px solid #ff4466; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            Vui lòng kiểm tra lại thông tin nhập vào!
        </div>
    @endif

    <form action="{{ route('admin.albums.update', $album->album_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tên album <span style="color: red;">*</span></label>
                <input type="text" name="title" value="{{ old('title', $album->title) }}" required style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Nghệ sĩ <span style="color: red;">*</span></label>
                <select name="artist_id" required style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
                    <option value="">-- Chọn nghệ sĩ --</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->artist_id }}" {{ old('artist_id', $album->artist_id) == $artist->artist_id ? 'selected' : '' }}>{{ $artist->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Năm phát hành</label>
                <input type="number" min="1900" max="2100" name="release_year" value="{{ old('release_year', $album->release_year) }}" placeholder="Ví dụ: 2025" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Ảnh bìa URL</label>
                <input type="text" name="image_url" value="{{ old('image_url', $album->image_url) }}" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Mô tả</label>
                <textarea name="description" style="width: 100%; min-height: 140px; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none; resize: vertical;">{{ old('description', $album->description) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn-add" style="margin-top: 20px; width: 100%; border: none; cursor: pointer; font-size: 1rem; text-align: center; display: block;">
            Cập nhật Album
        </button>

        <a href="{{ route('admin.albums.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #666; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </form>
</div>
</x-admin-layout>
