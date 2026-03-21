@extends('layouts.admin')
<style>
    /* Toolbar chứa các nút lọc và tìm kiếm */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #1a1c26;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.filter-tabs {
    display: flex;
    background: #12141d;
    padding: 5px;
    border-radius: 10px;
    gap: 5px;
}

.tab-link {
    padding: 8px 16px;
    color: #a0a0a0;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}

.tab-link.active {
    background: #00d1ff;
    color: #12141d;
    font-weight: bold;
}

.tab-link:hover:not(.active) {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
}

.search-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.input-group {
    position: relative;
}

.input-group i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #555;
}

.input-group input {
    background: #12141d;
    border: 1px solid #2d2f3b;
    color: #fff;
    padding: 10px 15px 10px 35px;
    border-radius: 8px;
    outline: none;
    width: 220px;
}

.btn-sort {
    background: #12141d;
    border: 1px solid #2d2f3b;
    color: #a0a0a0;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}
</style>
@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2><i class="fas fa-microphone-lines"></i> Quản lý Nghệ sĩ</h2>
            <p style="color: #888; font-size: 14px; margin-top: 5px;">Tổng số: {{ $artists->count() }} nghệ sĩ</p>
        </div>
        <a href="{{ route('admin.artists.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>

    <div class="toolbar">
        <form action="{{ route('admin.artists.index') }}" method="GET" class="search-form" id="filterForm">
            <div class="input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Tìm tên hoặc ID..." value="{{ request('search') }}">
            </div>

            <select name="sort" onchange="this.form.submit()" class="btn-sort">
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>⬇ Mới nhất</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>⬆ Cũ nhất</option>
            </select>
            
            <input type="hidden" name="type" value="{{ request('type', 'all') }}">
        </form>

        <div class="filter-tabs">
            <a href="{{ request()->fullUrlWithQuery(['type' => 'all']) }}" 
               class="tab-link {{ request('type', 'all') == 'all' ? 'active' : '' }}">Tất cả</a>
            
            <a href="{{ request()->fullUrlWithQuery(['type' => 'vn']) }}" 
               class="tab-link {{ request('type') == 'vn' ? 'active' : '' }}">Việt Nam</a>
            
            <a href="{{ request()->fullUrlWithQuery(['type' => 'inter']) }}" 
               class="tab-link {{ request('type') == 'inter' ? 'active' : '' }}">Quốc tế</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID <i class="fas fa-sort-down"></i></th>
                <th>Nghệ sĩ</th>
                <th>Tiểu sử (Bio)</th>
                <th style="text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($artists as $artist)
            <tr>
                <td style="color: #666;">#{{ $artist->artist_id }}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="{{ $artist->image_url ?? 'https://via.placeholder.com/45' }}" 
                             style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover;">
                        <div>
                            <div style="font-weight: bold;">{{ $artist->name }}</div>
                            <div style="font-size: 12px; color: #888;"><i class="fas fa-globe-asia"></i> {{ $artist->country }}</div>
                        </div>
                    </div>
                </td>
                <td style="color: #aaa; font-size: 14px; max-width: 400px;">
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $artist->bio ?? 'Chưa có tiểu sử...' }}
                    </div>
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.artists.edit', $artist->artist_id) }}" class="btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                    <form action="{{ route('admin.artists.destroy', $artist->artist_id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Xóa thật không Lead?')">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection