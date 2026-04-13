@extends('layouts.client-layout')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<h2>Đặt lại mật khẩu</h2>
<p style="color: #aaa; text-align: center; margin-bottom: 25px;">
    Nhập mật khẩu mới cho tài khoản của bạn.
</p>

@if ($errors->any())
    <div style="background: rgba(255, 68, 102, 0.15); color: #ff4466; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
        @foreach($errors->all() as $error)
            <small>{{ $error }}</small><br>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email của bạn" required>
    </div>
    
    <div class="input-group">
        <label>Mật khẩu mới</label>
        <input type="password" name="password" placeholder="Mật khẩu mới" required>
    </div>
    
    <div class="input-group">
        <label>Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required>
    </div>
    
    <button type="submit" class="btn-auth">Đặt lại mật khẩu</button>
</form>

<div class="auth-link">
    <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
</div>
@endsection