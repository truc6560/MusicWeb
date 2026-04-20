<x-admin-layout title="Thêm Thông báo">
<style>
    .form-container { max-width: 700px; background: #1a1c26; padding: 30px; border-radius: 16px; border: 1px solid #2d2f3b; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; color: #e8f4ff; font-weight: 600; margin-bottom: 8px; font-size: 14px; }
    .form-control { width: 100%; background: #12141d; border: 1px solid #3d404d; color: #fff; padding: 12px 14px; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-control:focus { outline: none; border-color: #00d1ff; box-shadow: 0 0 8px rgba(0, 209, 255, 0.3); }
    textarea.form-control { resize: vertical; min-height: 150px; }
    .btn-group { display: flex; gap: 12px; margin-top: 30px; border-top: 1px solid #2d2f3b; padding-top: 20px; }
    .btn-save { background: #00d1ff; color: #12141d; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-cancel { background: transparent; border: 1px solid #2d2f3b; color: #888; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: 600; }
    .btn-cancel:hover { color: #fff; border-color: #fff; }
    .type-options { display: flex; gap: 10px; flex-wrap: wrap; }
    .type-radio { display: none; }
    .type-label { padding: 10px 15px; border: 1px solid #3d404d; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .type-radio:checked + .type-label { background: #00d1ff; color: #12141d; border-color: #00d1ff; font-weight: 700; }
    .send-options { display: flex; gap: 10px; }
    .send-radio { display: none; }
    .send-label { padding: 10px 15px; border: 1px solid #3d404d; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .send-radio:checked + .send-label { background: #00d1ff; color: #12141d; border-color: #00d1ff; font-weight: 700; }
</style>

<div class="form-container">
    <div class="card-header" style="margin-bottom: 20px;">
        <h2 style="margin: 0;"><i class="fas fa-plus-circle" style="color: #00d1ff;"></i> Thêm Thông báo Mới</h2>
    </div>

    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Tiêu đề <span style="color: #ff4466;">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề thông báo" value="{{ old('title') }}" required>
            @error('title')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nội dung <span style="color: #ff4466;">*</span></label>
            <textarea name="content" class="form-control" placeholder="Nhập nội dung thông báo" required>{{ old('content') }}</textarea>
            @error('content')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">URL Ảnh (tùy chọn)</label>
            <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('image_url') }}">
            @error('image_url')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Loại thông báo <span style="color: #ff4466;">*</span></label>
            <div class="type-options">
                <div>
                    <input type="radio" id="type-info" name="type" value="info" class="type-radio" {{ old('type') == 'info' ? 'checked' : '' }} required>
                    <label for="type-info" class="type-label">
                        <i class="fas fa-info-circle"></i> Thông tin
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-success" name="type" value="success" class="type-radio" {{ old('type') == 'success' ? 'checked' : '' }}>
                    <label for="type-success" class="type-label">
                        <i class="fas fa-check-circle"></i> Thành công
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-warning" name="type" value="warning" class="type-radio" {{ old('type') == 'warning' ? 'checked' : '' }}>
                    <label for="type-warning" class="type-label">
                        <i class="fas fa-exclamation-circle"></i> Cảnh báo
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-error" name="type" value="error" class="type-radio" {{ old('type') == 'error' ? 'checked' : '' }}>
                    <label for="type-error" class="type-label">
                        <i class="fas fa-times-circle"></i> Lỗi
                    </label>
                </div>
            </div>
            @error('type')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Gửi đến <span style="color: #ff4466;">*</span></label>
            <div class="send-options">
                <div>
                    <input type="radio" id="send-all" name="send_to" value="all" class="send-radio" {{ old('send_to') == 'all' ? 'checked' : '' }} required>
                    <label for="send-all" class="send-label">
                        <i class="fas fa-users"></i> Tất cả người dùng
                    </label>
                </div>
                <div>
                    <input type="radio" id="send-admins" name="send_to" value="admins" class="send-radio" {{ old('send_to') == 'admins' ? 'checked' : '' }}>
                    <label for="send-admins" class="send-label">
                        <i class="fas fa-shield-alt"></i> Chỉ Admin
                    </label>
                </div>
            </div>
            @error('send_to')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Lưu & Gửi thông báo
            </button>
            <a href="{{ route('admin.notifications.index') }}" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>
</x-admin-layout>
