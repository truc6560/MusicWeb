<x-admin-layout title="Thêm Thể Loại Mới">
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: none; margin-bottom: 0;">
        <h2 style="color: #00d1ff; text-align: center; width: 100%;"><i class="fas fa-folder-plus"></i> Thêm Thể Loại Mới</h2>
    </div>

    @if ($errors->any())
        <div style="background: rgba(255, 68, 102, 0.2); color: #ff4466; border: 1px solid #ff4466; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            Vui lòng nhập đầy đủ các trường bắt buộc!
        </div>
    @endif

    <form action="{{ route('admin.genres.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tên thể loại <span style="color: red;">*</span></label>
            <input type="text" name="name" required placeholder="Ví dụ: Pop, Ballad, EDM"
                   style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Mô tả</label>
            <textarea name="description" placeholder="Nhập mô tả thể loại..."
                      style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none; height: 120px; resize: vertical;"></textarea>
        </div>

        <button type="submit" class="btn-add" style="width: 100%; border: none; cursor: pointer; font-size: 1rem; text-align: center; display: block;">
            Lưu Thể Loại
        </button>

        <a href="{{ route('admin.genres.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #666; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </form>
</div>
</x-admin-layout>
