<x-admin-layout title="Quản lý Người dùng">
<style>
    /* 1. Toolbar đồng bộ */
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

    .user-tools {
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
        width: 300px;
        transition: 0.3s;
    }

    .user-sort, .role-select {
        background: #12141d;
        border: 1px solid #3d404d;
        color: #fff;
        padding: 11px 15px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    /* 2. Role Tabs kiểu mới */
    .role-tabs {
        display: flex;
        background: #12141d;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #3d404d;
    }

    .role-tabs a {
        padding: 8px 16px;
        border-radius: 8px;
        color: #8d93a5;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
    }

    .role-tabs a.active {
        background: #00d1ff;
        color: #101322;
    }

    /* 3. Table Styling */
    table {
        border-radius: 12px;
        overflow: hidden;
    }

    th {
        background: #1f222e;
        color: #00d1ff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    /* 4. User Info */
    .user-id {
        font-family: monospace;
        color: #00d1ff;
        background: rgba(0, 209, 255, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #2d2f3b;
    }

    /* Badges */
    .role-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }
    .role-admin { background: rgba(189, 0, 255, 0.2); color: #bd00ff; border: 1px solid rgba(189, 0, 255, 0.3); }
    .role-user { background: rgba(255, 255, 255, 0.05); color: #a0a0a0; border: 1px solid #3d404d; }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .dot-active { background: #11f6b0; box-shadow: 0 0 10px rgba(17, 246, 176, 0.4); }
    .dot-banned { background: #ff4466; box-shadow: 0 0 10px rgba(255, 68, 102, 0.4); }

    /* 5. Buttons */
    .btn-toggle {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
    }
    .btn-ban { border: 1px solid #ff4466; color: #ff4466; }
    .btn-ban:hover { background: #ff4466; color: #fff; }
    .btn-unban { border: 1px solid #00d1ff; color: #00d1ff; }
    .btn-unban:hover { background: #00d1ff; color: #12141d; }
</style>

<div class="card">
    <div class="card-header">
        <div>
            <h2><i class="fas fa-users-cog" style="color: #00d1ff;"></i> Quản lý Người dùng</h2>
            <p style="color: #888; font-size: 14px; margin-top: 5px;">Hệ thống đang có <strong>{{ $users->count() }}</strong> tài khoản</p>
        </div>
    </div>

    <div class="toolbar">
        <div class="role-tabs">
            <a href="{{ request()->fullUrlWithQuery(['role' => 'all']) }}" class="{{ request('role', 'all') === 'all' ? 'active' : '' }}">Tất cả</a>
            <a href="{{ request()->fullUrlWithQuery(['role' => 'admin']) }}" class="{{ request('role') === 'admin' ? 'active' : '' }}">Admin</a>
            <a href="{{ request()->fullUrlWithQuery(['role' => 'user']) }}" class="{{ request('role') === 'user' ? 'active' : '' }}">User</a>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="user-tools">
            <div class="input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc email...">
            </div>

            <select class="user-sort" name="sort" onchange="this.form.submit()">
                <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>⬇ Mới nhất</option>
                <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>⬆ Cũ nhất</option>
            </select>
        </form>
    </div>

    <div class="user-table-wrap">
        <table class="user-table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Thành viên</th>
                    <th>Vai trò</th>
                    <th>Ngày tham gia</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right; padding-right: 25px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $isAdmin = (int) $user->is_admin === 1;
                    $isActive = strtolower((string) $user->status) === 'active' || empty($user->status);
                @endphp
                <tr>
                    <td><span class="user-id">#{{ $user->user_id }}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img class="user-avatar" src="{{ $user->avatar_url ?: 'https://ui-avatars.com/api/?name='.urlencode($user->username).'&background=random' }}" alt="">
                            <div>
                                <div style="font-weight: 700; color: #fff; font-size: 15px;">{{ $user->username }}</div>
                                <div style="color: #666; font-size: 12px;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge {{ $isAdmin ? 'role-admin' : 'role-user' }}">
                            <i class="fas {{ $isAdmin ? 'fa-shield-alt' : 'fa-user' }}" style="margin-right: 5px;"></i>
                            {{ $isAdmin ? 'ADMIN' : 'USER' }}
                        </span>
                    </td>
                    <td style="color: #a0a0a0; font-size: 13px;">
                        {{ $user->registration_date ? \Carbon\Carbon::parse($user->registration_date)->format('d/m/Y') : '--/--/----' }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; font-size: 13px; font-weight: 600; color: {{ $isActive ? '#11f6b0' : '#ff4466' }}">
                            <span class="status-dot {{ $isActive ? 'dot-active' : 'dot-banned' }}"></span>
                            {{ $isActive ? 'Hoạt động' : 'Đã khóa' }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; justify-content: flex-end;">
                            <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn-toggle {{ $isActive ? 'btn-ban' : 'btn-unban' }}" type="submit">
                                    <i class="fas {{ $isActive ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    {{ $isActive ? 'Khóa tài khoản' : 'Mở khóa' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 60px; color: #555;">
                        <i class="fas fa-users-slash" style="font-size: 40px; margin-bottom: 15px; display: block; opacity: 0.2;"></i>
                        Không tìm thấy người dùng nào phù hợp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>