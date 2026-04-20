<x-admin-layout title="Sửa Thông báo">
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
    .checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .checkbox-input { width: 20px; height: 20px; cursor: pointer; }
</style>

<div class="form-container">
    <div class="card-header" style="margin-bottom: 20px;">
        <h2 style="margin: 0;"><i class="fas fa-edit" style="color: #00d1ff;"></i> Sửa Thông báo #{{ $notification->id }}</h2>
    </div>

    <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Tiêu đề <span style="color: #ff4466;">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề thông báo" value="{{ $notification->title }}" required>
            @error('title')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nội dung <span style="color: #ff4466;">*</span></label>
            <textarea name="content" class="form-control" placeholder="Nhập nội dung thông báo" required>{{ $notification->content }}</textarea>
            @error('content')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">URL Ảnh (tùy chọn)</label>
            <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ $notification->image_url }}">
            @if($notification->image_url)
                <img src="{{ $notification->image_url }}" alt="Preview" style="margin-top: 10px; max-width: 200px; border-radius: 8px;">
            @endif
            @error('image_url')
                <p style="color: #ff4466; font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Loại thông báo <span style="color: #ff4466;">*</span></label>
            <div class="type-options">
                <div>
                    <input type="radio" id="type-info" name="type" value="info" class="type-radio" {{ $notification->type == 'info' ? 'checked' : '' }} required>
                    <label for="type-info" class="type-label">
                        <i class="fas fa-info-circle"></i> Thông tin
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-success" name="type" value="success" class="type-radio" {{ $notification->type == 'success' ? 'checked' : '' }}>
                    <label for="type-success" class="type-label">
                        <i class="fas fa-check-circle"></i> Thành công
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-warning" name="type" value="warning" class="type-radio" {{ $notification->type == 'warning' ? 'checked' : '' }}>
                    <label for="type-warning" class="type-label">
                        <i class="fas fa-exclamation-circle"></i> Cảnh báo
                    </label>
                </div>
                <div>
                    <input type="radio" id="type-error" name="type" value="error" class="type-radio" {{ $notification->type == 'error' ? 'checked' : '' }}>
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
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" class="checkbox-input" {{ $notification->is_active ? 'checked' : '' }}>
                <span>Bật thông báo này</span>
            </label>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.notifications.index') }}" class="btn-cancel">Quay lại</a>
        </div>
    </form>
</div>
</x-admin-layout>
