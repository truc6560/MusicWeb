@extends('layouts.client-layout')

@section('content')
<style>
    .profile-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .profile-grid {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    
    .profile-sidebar {
        flex: 0 0 280px;
        background: linear-gradient(135deg, #1a1c26 0%, #12141d 100%);
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        border: 1px solid rgba(138, 43, 226, 0.2);
        height: fit-content;
    }
    
    .profile-content {
        flex: 1;
        min-width: 0;
    }
    
    .profile-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
    }
    
    .profile-avatar img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #8A2BE2;
        box-shadow: 0 10px 30px rgba(138, 43, 226, 0.3);
    }
    
    .avatar-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8A2BE2, #4a00e0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        color: #fff;
        border: 4px solid #8A2BE2;
        box-shadow: 0 10px 30px rgba(138, 43, 226, 0.3);
    }
    
    .camera-icon {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #1ed760;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        border: 2px solid #fff;
    }
    
    .camera-icon:hover {
        transform: scale(1.1);
        background: #1fdf64;
    }
    
    .delete-avatar-btn {
        background: #ff4466;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        color: #fff;
        transition: 0.3s;
    }
    
    .delete-avatar-btn:hover {
        transform: scale(1.1);
        background: #ff6688;
    }
    
    .avatar-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
    }
    
    .profile-name {
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        margin: 15px 0 5px;
    }
    
    .profile-email {
        color: #aaa;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #2d2f3b;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 20px;
        font-weight: bold;
        color: #8A2BE2;
    }
    
    .stat-label {
        font-size: 11px;
        color: #aaa;
        text-transform: uppercase;
    }
    
    .info-card {
        background: #1a1c26;
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 20px;
        border: 1px solid rgba(138, 43, 226, 0.2);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #2d2f3b;
    }
    
    .card-header i {
        font-size: 24px;
        color: #8A2BE2;
    }
    
    .card-header h3 {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        margin: 0;
    }
    
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    
    .form-group label {
        display: block;
        color: #aaa;
        font-size: 12px;
        margin-bottom: 8px;
        text-transform: uppercase;
        font-weight: 500;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        background: #12141d;
        border: 1px solid #2d2f3b;
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        transition: 0.3s;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #8A2BE2;
    }
    
    .form-group input:disabled {
        color: #666;
        cursor: not-allowed;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #8A2BE2, #4a00e0);
        border: none;
        padding: 12px 24px;
        border-radius: 50px;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(138, 43, 226, 0.4);
    }
    
    .password-card {
        background: #1a1c26;
        border-radius: 20px;
        padding: 25px 30px;
        border: 1px solid rgba(255, 208, 0, 0.2);
    }
    
    .password-card .card-header i {
        color: #ffd000;
    }
    
    .btn-password {
        background: transparent;
        border: 2px solid #ffd000;
        padding: 12px 24px;
        border-radius: 50px;
        color: #ffd000;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-password:hover {
        background: #ffd000;
        color: #000;
    }
    
    .alert-success {
        background: rgba(30, 215, 96, 0.15);
        color: #1ed760;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(30, 215, 96, 0.3);
        font-size: 14px;
    }
    
    .alert-error {
        background: rgba(255, 68, 102, 0.15);
        color: #ff4466;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 68, 102, 0.3);
        font-size: 14px;
    }
    
    @media (max-width: 768px) {
        .profile-sidebar {
            flex: 0 0 100%;
        }
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<div class="profile-wrapper">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('client.home') }}" data-no-ajax="false" style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 14px; border-radius: 999px; text-decoration: none; color: #d6e0f0; background: #141824; border: 1px solid rgba(255, 255, 255, 0.08); font-weight: 700; font-size: 13px;">
            <i class="fas fa-arrow-left"></i>
            <span>Quay lại</span>
        </a>
    </div>

    @if(session('status'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <i class="fas fa-exclamation-circle"></i> {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="profile-grid">
        {{-- Cột trái: Avatar --}}
        <div class="profile-sidebar">
            <div class="profile-avatar">
                <img src="{{ $user->avatar_url ? asset($user->avatar_url) : asset('image/user.png') }}" alt="Avatar" id="profileAvatar">
                <label for="avatarFile" class="camera-icon">
                    <i class="fas fa-camera" style="font-size: 16px; color: #000;"></i>
                </label>
            </div> 
            <div class="avatar-actions">
                @if($user->avatar_url)
                    <button type="button" id="deleteAvatarBtn" class="delete-avatar-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endif
            </div>
                   
            <input type="file" name="avatar_url" id="avatarFile" style="display: none;" accept="image/*">
            
            <div class="profile-name">{{ $user->full_name ?? $user->username }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ (int) ($user->playlists_count ?? 0) }}</div>
                    <div class="stat-label">Playlists</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Followers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Following</div>
                </div>
                
            </div>
        </div>

        {{-- Cột phải: Nội dung --}}
        <div class="profile-content">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')
                
                <div class="info-card">
                    <div class="card-header">
                        <i class="fas fa-user-circle"></i>
                        <h3>Thông tin cá nhân</h3>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" value="{{ $user->username }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-save" id="btnSaveProfile">
                         Lưu thay đổi
                    </button>
                </div>
            </form>
            
            <form method="POST" action="{{ route('profile.change-password') }}" id="passwordForm">
                @csrf
                @method('PUT')
                
                <div class="info-card">
                    <div class="card-header">
                        <h3>Đổi mật khẩu</h3>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required placeholder="Nhập mật khẩu hiện tại">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="password" required placeholder="Nhập mật khẩu mới">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" required placeholder="Xác nhận mật khẩu">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-save" id="btnSaveProfile">
                        </i> Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Click camera -> chọn ảnh
    document.querySelector('.camera-icon').addEventListener('click', function() {
        document.getElementById('avatarFile').click();
    });
    
    // Khi chọn ảnh -> preview và upload
    document.getElementById('avatarFile').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            var file = e.target.files[0];
            var formData = new FormData();
            formData.append('avatar_url', file);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            
            // Preview ảnh
            var reader = new FileReader();
            reader.onload = function(ev) {
                var avatarDiv = document.getElementById('profileAvatar');
                avatarDiv.src = ev.target.result;
                
                // Cập nhật avatar header ngay lập tức
                if (window.updateHeaderAvatar) {
                    window.updateHeaderAvatar(ev.target.result);
                }
            };
            reader.readAsDataURL(file);
            
            // Upload ảnh bằng AJAX
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      showNotification('Cập nhật avatar thành công!', 'success');
                      // Reload để hiển thị ảnh mới
                      setTimeout(function() {
                          location.reload();
                      }, 500);
                  }
              }).catch(error => {
                  console.log('Lỗi upload:', error);
                  showNotification('Có lỗi xảy ra! Vui lòng thử lại.', 'error');
              });
        }
    });
    
       
    // Xử lý nút Lưu thay đổi
    document.getElementById('btnSaveProfile').addEventListener('click', function(e) {
        // Button đã là type="submit" nên sẽ submit form bình thường
        // Form sẽ tự động chuyển về trang chủ theo redirect trong controller
    });
    
    // Xử lý nút Đổi mật khẩu
    var changePasswordBtn = document.getElementById('btnChangePassword');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function(e) {
            // Button đã là type="submit" nên sẽ submit form bình thường
        });
    }
    
    // Hàm hiển thị thông báo
    function showNotification(message, type) {
        var alertDiv = document.createElement('div');
        alertDiv.className = type === 'success' ? 'alert-success' : 'alert-error';
        alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
        var container = document.querySelector('.profile-wrapper');
        container.insertBefore(alertDiv, container.firstChild);
        setTimeout(function() {
            alertDiv.remove();
        }, 3000);
    }

        // Xóa avatar
    var deleteBtn = document.getElementById('deleteAvatarBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa ảnh đại diện?')) {
                fetch('{{ route("profile.avatar.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      }
                  });
            }
        });
    } 
</script>
@endsection