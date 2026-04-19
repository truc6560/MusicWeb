<?php

use App\Http\Controllers\Admin\AdminArtistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ĐKController;
use App\Http\Controllers\Auth\ĐNController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// 1. TRANG CHỦ
Route::get('/', [HomeController::class, 'index'])->name('client.home');

// 2. AUTH ROUTES (KHÔNG CẦN ĐĂNG NHẬP)
Route::middleware('guest')->group(function () {
    Route::get('/register', [ĐKController::class, 'create'])->name('register');
    Route::post('/register', [ĐKController::class, 'store']);
    Route::get('/login', [ĐNController::class, 'create'])->name('login');
    Route::post('/login', [ĐNController::class, 'store']);
    Route::get('/login/phone', [ĐNController::class, 'phoneForm'])->name('login.phone.form');
    Route::post('/login/phone/send-otp', [ĐNController::class, 'sendOtp'])->name('login.phone.send-otp');
    Route::get('/login/phone/verify', [ĐNController::class, 'verifyOtpForm'])->name('login.phone.verify');
    Route::post('/login/phone/verify', [ĐNController::class, 'verifyOtp'])->name('login.phone.verify.post');
    Route::get('/login/google', [SocialAuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// 3. ĐĂNG XUẤT 
Route::post('/logout', [ĐNController::class, 'destroy'])->name('logout')->middleware('auth');

// 4. KHU VỰC ADMIN (yêu cầu đăng nhập)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('admin.artists.index');
    Route::get('/artists/create', [AdminArtistController::class, 'create'])->name('admin.artists.create');
    Route::post('/artists', [AdminArtistController::class, 'store'])->name('admin.artists.store');
    Route::get('/artists/{id}/edit', [AdminArtistController::class, 'edit'])->name('admin.artists.edit');
    Route::put('/artists/{id}', [AdminArtistController::class, 'update'])->name('admin.artists.update');
    Route::delete('/artists/{id}', [AdminArtistController::class, 'destroy'])->name('admin.artists.destroy');
});

// 5. KHU VỰC PROFILE (yêu cầu đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});


// Quên mật khẩu routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');
//Xóa avata
Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete')->middleware('auth'); 