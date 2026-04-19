<x-admin-layout title="Chỉnh sửa Bài viết">
<style>
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; color: #ffd000; font-weight: 600; font-size: 14px; }
    
    input[type="text"], textarea {
        width: 100%; padding: 12px 15px; background: #12141d; border: 1px solid #2d2f3b;
        color: #fff; border-radius: 10px; outline: none; font-size: 15px; transition: 0.3s;
    }
    input:focus, textarea:focus { border-color: #ffd000; box-shadow: 0 0 0 2px rgba(255, 208, 0, 0.1); }
    textarea { min-height: 250px; resize: vertical; line-height: 1.6; }

    .btn-group { display: flex; gap: 12px; margin-top: 30px; border-top: 1px solid #2d2f3b; padding-top: 20px; }
    .btn-update { background: #ffd000; color: #12141d; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-cancel { background: transparent; border: 1px solid #2d2f3b; color: #888; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: 600; }
</style>

<div class="card form-container">
    <div class="card-header" style="margin-bottom: 20px;">
        <h2 style="color: #ffd000;"><i class="fas fa-edit"></i> Chỉnh sửa bài viết #{{ $news->news_id }}</h2>
    </div>

    <form action="{{ route('admin.news.update', $news->news_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">Tiêu đề bài viết</label>
            <input type="text" name="title" id="title" value="{{ $news->title }}" required>
        </div>

        <div class="form-group">
            <label for="image_url">Ảnh bìa (URL)</label>
            <input type="text" name="image_url" id="image_url" value="{{ $news->image_url }}">
            @if($news->image_url)
                <img src="{{ $news->image_url }}" style="margin-top: 10px; width: 100px; border-radius: 5px; border: 1px solid #2d2f3b;">
            @endif
        </div>

        <div class="form-group">
            <label for="content">Nội dung bài viết</label>
            <textarea name="content" id="content" required>{{ $news->content }}</textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-update">
                <i class="fas fa-check-circle"></i> Cập nhật ngay
            </button>
            <a href="{{ route('admin.news.index') }}" class="btn-cancel">Quay lại</a>
        </div>
    </form>
</div>
</x-admin-layout>