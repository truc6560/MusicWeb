@extends('layouts.admin')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: none; margin-bottom: 0;">
        <h2 style="color: #ffd000; text-align: center; width: 100%;"><i class="fas fa-edit"></i> Chỉnh Sửa Nghệ Sĩ</h2>
    </div>

    <form action="{{ route('admin.artists.update', $artist->artist_id) }}" method="POST">
        @csrf 
        @method('PUT') <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tên nghệ sĩ <span style="color: red;">*</span></label>
            <input type="text" name="name" value="{{ $artist->name }}" required style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Quốc gia</label>
            <input type="text" name="country" value="{{ $artist->country }}" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Link Ảnh đại diện (URL)</label>
            <input type="text" name="image_url" value="{{ $artist->image_url }}" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #aaa; font-weight: 600;">Tiểu sử</label>
            <textarea name="bio" style="width: 100%; padding: 12px; background: #12141d; border: 1px solid #2d2f3b; color: #fff; border-radius: 8px; outline: none; height: 120px; resize: vertical;">{{ $artist->bio }}</textarea>
        </div>

        <button type="submit" style="width: 100%; border: none; cursor: pointer; font-size: 1rem; text-align: center; display: block; background: #ffd000; color: #12141d; padding: 12px; border-radius: 8px; font-weight: bold;">
            Cập nhật thay đổi
        </button>
        
        <a href="{{ route('admin.artists.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #666; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </form>
</div>
@endsection