<x-admin-layout title="Quản lý Bài hát">
<style>
    /* 1. Toolbar đồng bộ với trang Thể loại */
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

    /* 2. Table đồng bộ phong cách */
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

    /* 3. Chi tiết hiển thị bài hát */
    .song-id {
        font-family: 'Courier New', Courier, monospace;
        color: #00d1ff;
        background: rgba(0, 209, 255, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }

    .song-cover {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .badge-info {
        background: rgba(189, 0, 255, 0.1);
        color: #bd00ff;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        display: inline-block;
        margin-top: 4px;
    }

    .play-count {
        color: #2ecc71;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* 4. Action Buttons */
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
            <h2><i class="fas fa-music" style="color: #00d1ff;"></i> Quản lý Bài hát</h2>
            <p style="color: #888; font-size: 14px; margin-top: 5px;">
                Tổng cộng <strong>{{ $songs->count() }}</strong> bài hát trong thư viện
            </p>
        </div>
        <a href="{{ route('admin.songs.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Thêm bài hát mới
        </a>
    </div>

    <div class="toolbar">
        <form action="{{ route('admin.songs.index') }}" method="GET" class="search-form">
            <div class="input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Tìm tên bài hát, nghệ sĩ..." value="{{ request('search') }}">
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
                <th>Thông tin bài hát</th>
                <th>Nghệ sĩ / Album</th>
                <th>Lượt nghe</th>
                <th style="text-align: right; padding-right: 25px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($songs as $song)
            <tr>
                <td><span class="song-id">#{{ $song->song_id }}</span></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img class="song-cover" src="{{ $song->image_url ?: 'https://via.placeholder.com/50' }}" alt="">
                        <div>
                            <div style="font-weight: bold; color: #fff;">{{ $song->title }}</div>
                            <div class="song-meta">
                                <span class="badge-info">{{ $song->genres ?: 'N/A' }}</span>
                                <span style="font-size: 11px; margin-left: 8px;"><i class="far fa-clock"></i> {{ $song->duration }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="color: #fff;">{{ optional($song->artist)->name }}</div>
                    <div style="color: #666; font-size: 12px;">{{ optional($song->album)->title ?: 'Single' }}</div>
                </td>
                <td>
                    <div class="play-count">
                        <i class="fas fa-headphones"></i>
                        {{ number_format($song->plays) }}
                    </div>
                </td>
                <td>
                    <div class="btn-action-group">
                        <a href="{{ route('admin.songs.edit', $song->song_id) }}" class="btn-edit">
                            <i class="fas fa-pen"></i> Sửa
                        </a>
                        <form action="{{ route('admin.songs.destroy', $song->song_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Xóa bài hát này?')">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 50px; color: #666;">
                    <i class="fas fa-compact-disc fa-spin" style="font-size: 30px; margin-bottom: 10px;"></i>
                    <p>Chưa có bài hát nào được cập nhật.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-admin-layout>