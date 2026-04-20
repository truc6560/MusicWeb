@extends('layouts.client-layout')

@section('title', 'Đăng nhập bằng số điện thoại')

@section('content')
<h2>Đăng nhập bằng số điện thoại</h2>

<form method="POST" action="{{ route('login.phone.send-otp') }}">
    @csrf

    <div class="input-group">
        <label>Số điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Nhập số điện thoại" required autofocus>
        @error('phone') <div class="error-message">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn-auth">Gửi mã OTP</button>
</form>

@if(session('success'))
    <div class="success-message">{{ session('success') }}</div>
@endif

<div class="auth-link">
    Hoặc <a href="{{ route('login') }}">quay lại đăng nhập bằng email/tên đăng nhập</a>
</div>
@endsection
