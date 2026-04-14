<x-admin-layout title="Quản lý Album">
<style>
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #1a1c26;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 25px;
        border: 1px solid #2d2f3b;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .search-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .input-group {
        position: relative;
    }

    .input-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #00d1ff;
    }

    .input-group input {
        background: #12141d;
        border: 1px solid #3d404d;
        color: #fff;
        padding: 12px 15px 12px 42px;
        border-radius: 10px;
        outline: none;
        width: 350px;
        transition: 0.3s;
    }

    .input-group input:focus {
        border-color: #00d1ff;
        box-shadow: 0 0 0 2px rgba(0, 209, 255, 0.1);
    }

    .btn-sort {
        background: #12141d;
        border: 1px solid #3d404d;
        color: #fff;
        padding: 11px 15px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    table {
        border-radius: 12px;
        overflow: hidden;
    }

    th {
        background: #1f222e;
        color: #00d1ff;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    tbody tr {
        transition: 0.2s;
    }

    tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .album-id {
        font-family: 'Courier New', Courier, monospace;
        color: #00d1ff;
        background: rgba(0, 209, 255, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }

    .album-cover {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .year-badge {
        background: rgba(255, 208, 0, 0.12);
        color: #ffd000;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        display: inline-block;
        margin-top: 4px;
    }

    .btn-action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-edit, .btn-delete {
        padding: 8px 12px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
        border-radius: 8px;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-edit { border: 1px solid #ffd000; color: #ffd000; }
    .btn-edit:hover { background: #ffd000; color: #12141d; }

    .btn-delete { border: 1px solid #ff4466; color: #ff4466; background: transparent; cursor: pointer; }
    .btn-delete:hover { background: #ff4466; color: #fff; }
</style>

<div class="card">
    <div class="card-header">
        <div>
            <h2><i class="fas fa-compact-disc" style="color: #00d1ff;"></i> Quản lý Album</h2>
            <p style="color: #888; font-size: 14px; margin-top: 5px;">
                Tổng cộng <strong>{{ $albums->count() }}</strong> album
            </p>
        </div>
        <a href="{{ route('admin.albums.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Thêm album mới
        </a>
    </div>

    <div class="toolbar">
        <form action="{{ route('admin.albums.index') }}" method="GET" class="search-form">
            <div class="input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Tìm tên album, nghệ sĩ..." value="{{ request('search') }}">
            </div>

            <select name="sort" onchange="this.form.submit()" class="btn-sort">
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>⬇ Mới nhất</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>⬆ Cũ nhất</option>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th width="80">ID</th>
                <th>Thông tin album</th>
                <th>Nghệ sĩ</th>
                <th style="text-align: right; padding-right: 25px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($albums as $album)
            <tr>
                <td><span class="album-id">#{{ $album->album_id }}</span></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img class="album-cover" src="{{ $album->image_url ?: 'https://via.placeholder.com/54' }}" alt="">
                        <div>
                            <div style="font-weight: bold; color: #fff;">{{ $album->title }}</div>
                            <span class="year-badge">{{ $album->release_year ?: 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td style="color: #fff;">{{ optional($album->artist)->name ?: 'N/A' }}</td>
                <td>
                    <div class="btn-action-group">
                        <a href="{{ route('admin.albums.edit', $album->album_id) }}" class="btn-edit">
                            <i class="fas fa-pen"></i> Sửa
                        </a>
                        <form action="{{ route('admin.albums.destroy', $album->album_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Xóa album này?')">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 50px; color: #666;">
                    <i class="fas fa-compact-disc fa-spin" style="font-size: 30px; margin-bottom: 10px;"></i>
                    <p>Chưa có album nào được cập nhật.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-admin-layout>
