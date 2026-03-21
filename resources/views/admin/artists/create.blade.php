@extends('layouts.admin')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: none; margin-bottom: 0;">
        <h2 style="color: #00d1ff; text-align: center; width: 100%;"><i class="fas fa-user-plus"></i> Thêm Nghệ Sĩ Mới</h2>
    </div>

    @if ($errors->any())
        <div style="background: rgba(255, 68, 102, 0.2); color: #ff4466; border: 1px solid #ff4466; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            Vui lòng nhập đầy đủ các trường bắt buộc!
        </div>
    @endif

    <form action="{{ route('admin.artists.store') }}" method="POST">
        @csrf 

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tên nghệ sĩ <span style="color: red;">*</span></label>
            <input type="text" name="name" required placeholder="Ví dụ: Sơn Tùng M-TP" 
                   style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Quốc gia</label>
            <input type="text" name="country" placeholder="Ví dụ: Việt Nam" 
                   style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Link Ảnh đại diện (URL)</label>
            <input type="text" name="image_url" placeholder="https://example.com/image.jpg" 
                   style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tiểu sử</label>
            <textarea name="bio" placeholder="Nhập thông tin giới thiệu..." 
                      style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none; height: 120px; resize: vertical;"></textarea>
        </div>

        <button type="submit" class="btn-add" style="width: 100%; border: none; cursor: pointer; font-size: 1rem; text-align: center; display: block;">
            Lưu Nghệ Sĩ
        </button>
        
        <a href="{{ route('admin.artists.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #666; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </form>
</div>
@endsection