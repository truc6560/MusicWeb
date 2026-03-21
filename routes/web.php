<?php
use App\Http\Controllers\Admin\AdminArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// 1. TRANG CHỦ (Đúng chuẩn MVC)
Route::get('/', [HomeController::class, 'index'])->name('client.home');

// 2. KHU VỰC ADMIN
Route::prefix('admin')->group(function () {
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('admin.artists.index');
    Route::get('/artists/create', [AdminArtistController::class, 'create'])->name('admin.artists.create');
    Route::post('/artists', [AdminArtistController::class, 'store'])->name('admin.artists.store');
    Route::get('/artists/{id}/edit', [AdminArtistController::class, 'edit'])->name('admin.artists.edit');
    Route::put('/artists/{id}', [AdminArtistController::class, 'update'])->name('admin.artists.update');
    Route::delete('/artists/{id}', [AdminArtistController::class, 'destroy'])->name('admin.artists.destroy');
});