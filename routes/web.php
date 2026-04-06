<?php
use App\Http\Controllers\Admin\AdminArtistController;
use App\Http\Controllers\Admin\AdminGenreController;
use App\Http\Controllers\Admin\AdminSongController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StatisticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// 1. TRANG CHỦ (Đúng chuẩn MVC)
Route::get('/', [HomeController::class, 'index'])->name('client.home');

// 2. KHU VỰC ADMIN
Route::prefix('admin')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.home');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');

    Route::get('/news', [AdminNewsController::class, 'index'])->name('admin.news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('admin.news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');

    Route::get('/artists', [AdminArtistController::class, 'index'])->name('admin.artists.index');
    Route::get('/artists/create', [AdminArtistController::class, 'create'])->name('admin.artists.create');
    Route::post('/artists', [AdminArtistController::class, 'store'])->name('admin.artists.store');
    Route::get('/artists/{id}/edit', [AdminArtistController::class, 'edit'])->name('admin.artists.edit');
    Route::put('/artists/{id}', [AdminArtistController::class, 'update'])->name('admin.artists.update');
    Route::delete('/artists/{id}', [AdminArtistController::class, 'destroy'])->name('admin.artists.destroy');

    Route::get('/songs', [AdminSongController::class, 'index'])->name('admin.songs.index');
    Route::get('/songs/create', [AdminSongController::class, 'create'])->name('admin.songs.create');
    Route::post('/songs', [AdminSongController::class, 'store'])->name('admin.songs.store');
    Route::get('/songs/{id}/edit', [AdminSongController::class, 'edit'])->name('admin.songs.edit');
    Route::put('/songs/{id}', [AdminSongController::class, 'update'])->name('admin.songs.update');
    Route::delete('/songs/{id}', [AdminSongController::class, 'destroy'])->name('admin.songs.destroy');

    Route::get('/genres', [AdminGenreController::class, 'index'])->name('admin.genres.index');
    Route::get('/genres/create', [AdminGenreController::class, 'create'])->name('admin.genres.create');
    Route::post('/genres', [AdminGenreController::class, 'store'])->name('admin.genres.store');
    Route::get('/genres/{id}/edit', [AdminGenreController::class, 'edit'])->name('admin.genres.edit');
    Route::put('/genres/{id}', [AdminGenreController::class, 'update'])->name('admin.genres.update');
    Route::delete('/genres/{id}', [AdminGenreController::class, 'destroy'])->name('admin.genres.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
});