<x-admin-layout title="Quản lý Thể loại">

    <style>
        /* Nâng cấp Toolbar */
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
            color: #00d1ff; /* Đổi màu icon search cho nổi bật */
        }

        .input-group input {
            background: #12141d;
            border: 1px solid #3d404d;
            color: #fff;
            padding: 12px 15px 12px 42px;
            border-radius: 10px;
            outline: none;
            width: 320px;
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

        /* Tối ưu Bảng */
        table {
            border-radius: 12px;
            overflow: hidden;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        th {
            background: #1f222e;
            color: #00d1ff;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .genre-name {
            font-size: 16px;
            color: #fff;
            text-decoration: none;
        }

        .genre-id {
            font-family: 'Courier New', Courier, monospace;
            color: #00d1ff;
            background: rgba(0, 209, 255, 0.1);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        /* Chỉnh lại Nút bấm */
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
        }

        .btn-edit {
            border: 1px solid #ffd000;
            color: #ffd000;
        }

        .btn-edit:hover {
            background: #ffd000;
            color: #12141d;
        }

        .btn-delete {
            border: 1px solid #ff4466;
            color: #ff4466;
            background: transparent;
        }

        .btn-delete:hover {
            background: #ff4466;
            color: #fff;
        }

        .empty-state {
            padding: 60px !important;
            text-align: center;
        }

        .empty-state i {
            font-size: 48px;
            color: #2d2f3b;
            margin-bottom: 15px;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <div>
                <h2><i class="fas fa-tags" style="color: #00d1ff;"></i> Quản lý Thể loại</h2>
                <p style="color: #888; font-size: 14px; margin-top: 5px;">
                    Đang hiển thị <strong>{{ $genres->count() }}</strong> thể loại âm nhạc
                </p>
            </div>
            <a href="{{ route('admin.genres.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> Thêm thể loại mới
            </a>
        </div>

        <div class="toolbar">
            <form action="{{ route('admin.genres.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Tìm theo tên hoặc mô tả..." value="{{ request('search') }}">
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
                    <th width="100">Mã ID</th>
                    <th>Tên thể loại</th>
                    <th>Mô tả chi tiết</th>
                    <th style="text-align: right; padding-right: 25px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($genres as $genre)
                <tr>
                    <td><span class="genre-id">#{{ $genre->genre_id }}</span></td>
                    <td>
                        <div class="genre-name">{{ $genre->name }}</div>
                    </td>
                    <td style="color: #aaa; font-size: 14px; max-width: 400px;">
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $genre->description }}">
                            {{ $genre->description ?? 'Chưa có thông tin mô tả cho thể loại này.' }}
                        </div>
                    </td>
                    <td>
                        <div class="btn-action-group">
                            <a href="{{ route('admin.genres.edit', $genre->genre_id) }}" class="btn-edit">
                                <i class="fas fa-pen"></i> Sửa
                            </a>
                            <form action="{{ route('admin.genres.destroy', $genre->genre_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại này không?')">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p style="color: #666;">Không tìm thấy kết quả phù hợp nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>