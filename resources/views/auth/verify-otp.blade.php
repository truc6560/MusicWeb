@extends('layouts.client-layout')

@section('title', 'Xác nhận mã OTP')

@section('content')
<h2>Nhập mã OTP</h2>

<form method="POST" action="{{ route('login.phone.verify.post') }}">
    @csrf

    <div class="input-group">
        <label>Mã OTP</label>
        <input type="text" name="otp" placeholder="Nhập mã OTP 6 chữ số" required autofocus>
        @error('otp') <div class="error-message">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn-auth">Xác nhận</button>
</form>

<div class="auth-link">
    <a href="{{ route('login.phone.form') }}">Quay lại</a>
</div>
@endsection