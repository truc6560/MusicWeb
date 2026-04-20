<x-admin-layout title="Quản lý Thông báo">
<style>
    .toolbar { display: flex; justify-content: space-between; align-items: center; background: #1a1c26; padding: 20px; border-radius: 16px; margin-bottom: 25px; border: 1px solid #2d2f3b; }
    .noti-img { width: 80px; height: 60px; border-radius: 8px; object-fit: cover; }
    .noti-id { font-family: monospace; color: #00d1ff; background: rgba(0, 209, 255, 0.1); padding: 4px 8px; border-radius: 6px; font-size: 12px; }
    .btn-edit { border: 1px solid #ffd000; color: #ffd000; padding: 8px 12px; border-radius: 8px; text-decoration: none; }
    .btn-resend { border: 1px solid #00d1ff; color: #00d1ff; padding: 8px 12px; border-radius: 8px; text-decoration: none; margin-right: 5px; }
    .btn-delete { border: 1px solid #ff4466; color: #ff4466; background: transparent; padding: 8px 12px; border-radius: 8px; cursor: pointer; }
    .badge-info { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; }
    .badge-info-type { background: rgba(0, 209, 255, 0.2); color: #00d1ff; }
    .badge-success-type { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
    .badge-warning-type { background: rgba(255, 180, 0, 0.2); color: #ffb400; }
    .badge-error-type { background: rgba(255, 68, 102, 0.2); color: #ff4466; }
    .badge-active { background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 4px 10px; border-radius: 6px; font-size: 12px; }
</style>

<div class="card">
    <div class="card-header">
        <div>
            <h2><i class="fas fa-bell" style="color: #00d1ff;"></i> Quản lý Thông báo</h2>
            <p style="color: #888; font-size: 14px; margin-top: 5px;">Tổng cộng <strong>{{ $notifications->total() }}</strong> thông báo</p>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Thêm thông báo
        </a>
    </div>

    <div class="toolbar">
        <form action="{{ route('admin.notifications.index') }}" method="GET" style="display:flex; gap:10px; flex:1;">
            <div class="input-group" style="position:relative; flex:1;">
                <i class="fas fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#00d1ff;"></i>
                <input type="text" name="search" placeholder="Tìm kiếm thông báo..." value="{{ request('search') }}" style="background: #12141d; border: 1px solid #3d404d; color: #fff; padding: 10px 10px 10px 40px; border-radius: 8px; width: 100%; font-size: 14px;">
            </div>
            <button type="submit" style="background: #00d1ff; color: #12141d; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer;">Tìm kiếm</button>
        </form>
    </div>

    @if(session('success'))
        <div style="background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.3); color: #2ecc71; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <thead style="border-bottom: 2px solid #2d2f3b;">
            <tr>
                <th style="padding: 15px; text-align: left; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">ID</th>
                <th style="padding: 15px; text-align: left; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">Tiêu đề</th>
                <th style="padding: 15px; text-align: left; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">Loại</th>
                <th style="padding: 15px; text-align: left; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">Trạng thái</th>
                <th style="padding: 15px; text-align: left; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">Ngày tạo</th>
                <th style="padding: 15px; text-align: center; color: #888; font-weight: 700; font-size: 13px; text-transform: uppercase;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notification)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;">
                    <td style="padding: 15px; color: #fff;">
                        <span class="noti-id">#{{ $notification->id }}</span>
                    </td>
                    <td style="padding: 15px; color: #fff;">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if($notification->image_url)
                                <img src="{{ $notification->image_url }}" alt="{{ $notification->title }}" class="noti-img" style="width: 50px; height: 50px; border-radius: 6px; object-fit: cover;">
                            @endif
                            <div>
                                <p style="margin: 0; font-weight: 600; color: #fff;">{{ $notification->title }}</p>
                                <p style="margin: 0; font-size: 12px; color: #888; margin-top: 4px;">{{ Str::limit($notification->content, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px; color: #fff;">
                        <span class="badge-info badge-{{ $notification->type }}-type">{{ ucfirst($notification->type) }}</span>
                    </td>
                    <td style="padding: 15px; color: #fff;">
                        @if($notification->is_active)
                            <span class="badge-active"><i class="fas fa-check-circle"></i> Đang hoạt động</span>
                        @else
                            <span style="background: rgba(255, 68, 102, 0.2); color: #ff4466; padding: 4px 10px; border-radius: 6px; font-size: 12px;"><i class="fas fa-times-circle"></i> Tắt</span>
                        @endif
                    </td>
                    <td style="padding: 15px; color: #888; font-size: 13px;">
                        {{ $notification->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="btn-edit" style="padding: 6px 10px; font-size: 12px;">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.notifications.resend', $notification->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-resend" style="padding: 6px 10px; font-size: 12px; border: none; background: none; cursor: pointer;">
                                    <i class="fas fa-redo"></i> Gửi lại
                                </button>
                            </form>
                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #888;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Chưa có thông báo nào</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $notifications->links() }}
    </div>
</div>
</x-admin-layout>
