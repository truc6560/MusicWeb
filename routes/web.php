<?php
use App\Http\Controllers\Admin\AdminArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\User\InteractionController;
use App\Http\Controllers\User\PlaylistController;

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
    Route::post('/albums/toggle-like', [AlbumController::class, 'toggleLike'])->name('albums.toggleLike');
});

//GLOBAL PLAYER
Route::get('/song/{id}', [SongController::class, 'chitietbaihat'])->name('song.details');

// 3. KHU VỰC NGƯỜI DÙNG (Thả tim, Playlist, Lịch sử nghe)
Route::middleware('auth')->group(function () {
    Route::get('/my-playlists', [PlaylistController::class, 'index'])->name('playlist.index');
    Route::post('/my-playlists/create', [PlaylistController::class, 'store'])->name('playlist.store');
    Route::post('/ajax/playlist/add-song', [PlaylistController::class, 'addSongToPlaylist']);

    //Thả tim
    Route::post('/ajax/like-song', [InteractionController::class, 'toggleLikeSong']);
    Route::post('/ajax/like-artist', [InteractionController::class, 'toggleLikeArtist']);

    //Lịch sử & Lượt nghe
    Route::post('/ajax/record-history', [InteractionController::class, 'recordHistory']);
    Route::post('/ajax/increment-view', [InteractionController::class, 'incrementPlayCount']);
});

