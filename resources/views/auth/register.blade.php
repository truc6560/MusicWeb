<x-auth-layout title="Đăng ký">
<h2>Tạo tài khoản mới</h2>

<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="input-group">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" value="{{ old('username') }}" placeholder="Tên đăng nhập" required>
        @error('username') <div class="error-message">{{ $message }}</div> @enderror
    </div>
    
    <div class="input-group">
        <label>Họ và tên</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Họ và tên" required>
        @error('full_name') <div class="error-message">{{ $message }}</div> @enderror
    </div>
    
    <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        @error('email') <div class="error-message">{{ $message }}</div> @enderror
    </div>
    
    <div class="input-group">
        <label>Mật khẩu</label>
        <input type="password" name="password" placeholder="Mật khẩu" autocomplete="new-password" required>
        <div style="margin-top: 6px; font-size: 12px; color: #9aa3b2; line-height: 1.5;">
            Yêu cầu mật khẩu: tối thiểu 8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt
            (ví dụ: <strong style="color:#cdd6e6;">Abc@1234</strong>).
        </div>
        @error('password') <div class="error-message">{{ $message }}</div> @enderror
    </div>
    
    <div class="input-group">
        <label>Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
    </div>
    
    <button type="submit" class="btn-auth">Đăng ký</button>
</form>

<div class="divider">
    <div class="divider-line"></div>
    <div class="divider-text">hoặc</div>
    <div class="divider-line"></div>
</div>

<button class="btn-outline" onclick="alert('Tính năng đang phát triển')">
    <i class="fab fa-google"></i> Tiếp tục bằng Google
</button>

<div class="auth-link">
    Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
</div>
</x-auth-layout>