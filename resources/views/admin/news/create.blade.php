<x-admin-layout title="Thêm Tin Tức Mới">
<style>
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; color: #00d1ff; font-weight: 600; font-size: 14px; }
    
    input[type="text"], textarea {
        width: 100%; padding: 12px 15px; background: #12141d; border: 1px solid #2d2f3b;
        color: #fff; border-radius: 10px; outline: none; font-size: 15px; transition: 0.3s;
    }
    input:focus, textarea:focus { border-color: #00d1ff; box-shadow: 0 0 0 2px rgba(0, 209, 255, 0.1); }
    textarea { min-height: 250px; resize: vertical; line-height: 1.6; }

    .btn-group { display: flex; gap: 12px; margin-top: 30px; border-top: 1px solid #2d2f3b; padding-top: 20px; }
    .btn-save { background: #00d1ff; color: #12141d; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-cancel { background: transparent; border: 1px solid #2d2f3b; color: #888; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: 600; }
    .btn-cancel:hover { color: #fff; border-color: #fff; }
</style>

<div class="card form-container">
    <div class="card-header" style="margin-bottom: 20px;">
        <h2><i class="fas fa-plus-circle"></i> Tạo bài viết mới</h2>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Tiêu đề bài viết</label>
            <input type="text" name="title" id="title" placeholder="Nhập tiêu đề hấp dẫn..." required>
        </div>

        <div class="form-group">
            <label for="image_url">Ảnh bìa (URL)</label>
            <input type="text" name="image_url" id="image_url" placeholder="https://example.com/image.jpg">
        </div>

        <div class="form-group">
            <label for="content">Nội dung chi tiết</label>
            <textarea name="content" id="content" placeholder="Viết nội dung tin tức tại đây..." required></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Đăng bài viết
            </button>
            <a href="{{ route('admin.news.index') }}" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>
</x-admin-layout>