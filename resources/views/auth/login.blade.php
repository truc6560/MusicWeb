<x-auth-layout title="Đăng nhập">
<h2>Chào mừng bạn quay trở lại</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="input-group">
        <label>Email hoặc tên đăng nhập</label>
        <input type="text" name="username" value="{{ old('username') }}" placeholder="Email hoặc tên đăng nhập" required autofocus>
        @error('username')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="input-group">
        <label>Mật khẩu</label>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn-auth">Tiếp tục</button>
     
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="{{ route('password.request') }}" style="color: #8A2BE2; font-size: 13px; text-decoration: none;">
            Quên mật khẩu?
        </a>
    </div>
</form>

<div class="divider">
    <div class="divider-line"></div>
    <div class="divider-text">hoặc</div>
    <div class="divider-line"></div>
</div>

<button class="btn-outline" onclick="alert('Tính năng đang phát triển')">
    <i class="fas fa-phone-alt"></i> Tiếp tục bằng số điện thoại
</button>

<button class="btn-outline" onclick="alert('Tính năng đang phát triển')">
    <i class="fab fa-google"></i> Tiếp tục bằng Google
</button>


<div class="auth-link">
    Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay!</a>
</div>
</x-auth-layout>