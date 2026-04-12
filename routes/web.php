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
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\InteractionController;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\PlaylistController;
use App\Http\Controllers\NewReleaseController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\GenreController;

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
// 3. KHU VỰC ALBUMS
//Trang danh sách albums
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
// Trang chi tiết album
    Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('albums.show');
    Route::post('/albums/toggle-like', [AlbumController::class, 'toggleLike'])->name('albums.toggleLike');

// Tìm kiếm gợi ý
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// 4. THƯ VIỆN CÁ NHÂN
Route::get('/library/songs', [LibraryController::class, 'songs'])->name('library.songs');
Route::get('/library/artists', [LibraryController::class, 'artists'])->name('library.artists');
Route::get('/library/history', [LibraryController::class, 'history'])->name('library.history');

// 4. KHU VỰC NGHỆ SĨ
    Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
    Route::post('/artists/toggle-follow', [ArtistController::class, 'toggleFollow'])->name('artists.toggleFollow');

// 5. KHU VỰC THỂ LOẠI
Route::get('/genres/{id}', [GenreController::class, 'show'])->name('genres.show');

//GLOBAL PLAYER
Route::get('/song/{id}/stream', [SongController::class, 'stream'])->name('song.stream');
Route::get('/song/{id}', [SongController::class, 'thongtinbaihat'])->name('song.information');
Route::get('/song/{id}/laylyrics', [SongController::class, 'laylyrics'])->name('song.laylyrics');
Route::get('/song/{id}/chitiet', [SongController::class, 'chitietbaihat'])->name('song.details');


// 3. KHU VỰC NGƯỜI DÙNG (Thả tim, Playlist, Lịch sử nghe)
Route::middleware('auth')->group(function () {
    Route::get('/my-playlists', [PlaylistController::class, 'index'])->name('playlist.index');
    Route::post('/my-playlists/create', [PlaylistController::class, 'store'])->name('playlist.store');
    Route::post('/ajax/playlist/add-song', [PlaylistController::class, 'addSongToPlaylist'])->name('playlist.add-song');

    //Thả tim
    Route::get('/ajax/like-song/status', [InteractionController::class, 'likeSongStatus']);
    Route::post('/ajax/like-song', [InteractionController::class, 'toggleLikeSong']);
    Route::post('/ajax/like-artist', [InteractionController::class, 'toggleLikeArtist']);

    //Lịch sử & Lượt nghe
    Route::post('/ajax/record-history', [InteractionController::class, 'recordHistory']);
    Route::post('/ajax/increment-view', [InteractionController::class, 'incrementPlayCount']);
});

    //KHU VỰC PHÁT HÀNH MỚI
    Route::get('/new-releases', [NewReleaseController::class, 'index'])->name('new_releases');
    //Khu vực bảng xếp hạng
    Route::get('/charts', [ChartController::class, 'index'])->name('charts');

