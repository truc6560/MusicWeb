<?php
use App\Http\Controllers\Admin\AdminArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;

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
// 3. KHU VỰC ALBUMS
//Trang danh sách albums
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
// Trang chi tiết album
    Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('albums.show');
    Route::post('/albums/toggle- like', [AlbumController::class, 'toggleLike'])->name('albums.toggleLike');
// 4. KHU VỰC NGHỆ SĨ
    Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
    Route::post('/artists/toggle-follow', [ArtistController::class, 'toggleFollow'])->name('artists.toggleFollow');