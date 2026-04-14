<x-auth-layout title="Quên mật khẩu">
<h2>Quên mật khẩu?</h2>
<p style="color: #aaa; text-align: center; margin-bottom: 25px;">
    Nhập email của bạn để nhận link đặt lại mật khẩu.
</p>

@if (session('status'))
    <div style="background: rgba(30, 215, 96, 0.15); color: #1ed760; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
        {!! session('status') !!}
    </div>
@endif

@if ($errors->any())
    <div style="background: rgba(255, 68, 102, 0.15); color: #ff4466; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
        @foreach($errors->all() as $error)
            <small>{{ $error }}</small><br>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    
    <div class="input-group">
        <label>Email đăng ký</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Nhập email của bạn" required>
    </div>
    
    <button type="submit" class="btn-auth">Gửi link đặt lại mật khẩu</button>
</form>

<div class="auth-link">
    <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
</div>
</x-auth-layout>