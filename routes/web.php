<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Admin\AdminAlbumController;
use App\Http\Controllers\Admin\AdminArtistController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminGenreController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSongController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\ĐKController;
use App\Http\Controllers\Auth\ĐNController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewReleaseController;
use App\Http\Controllers\NotificationApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\User\InteractionController;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\PlaylistController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('client.home');

Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('albums.show');
Route::post('/albums/toggle-like', [AlbumController::class, 'toggleLike'])->name('albums.toggleLike');

Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/library/songs', [LibraryController::class, 'songs'])->name('library.songs');
Route::get('/library/artists', [LibraryController::class, 'artists'])->name('library.artists');
Route::get('/library/history', [LibraryController::class, 'history'])->name('library.history');

Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
Route::post('/artists/toggle-follow', [ArtistController::class, 'toggleFollow'])->name('artists.toggleFollow');

Route::get('/genres/{id}', [GenreController::class, 'show'])->name('genres.show');

Route::get('/song/{id}/stream', [SongController::class, 'stream'])->name('song.stream');
Route::get('/song/{id}', [SongController::class, 'thongtinbaihat'])->name('song.information');
Route::get('/song/{id}/laylyrics', [SongController::class, 'laylyrics'])->name('song.laylyrics');
Route::get('/song/{id}/chitiet', [SongController::class, 'chitietbaihat'])->name('song.details');
Route::post('/ajax/increment-view', [InteractionController::class, 'incrementPlayCount']);

Route::get('/new-releases', [NewReleaseController::class, 'index'])->name('new_releases');
Route::get('/charts', [ChartController::class, 'index'])->name('charts');

// 2. GUEST AUTH ROUTES (KHÔNG CẦN ĐĂNG NHẬP)
Route::middleware('guest')->group(function () {
    Route::get('/register', [ĐKController::class, 'create'])->name('register');
    Route::post('/register', [ĐKController::class, 'store']);
    Route::get('/login', [ĐNController::class, 'create'])->name('login');
    Route::post('/login', [ĐNController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::get('/login/phone', [ĐNController::class, 'phoneForm'])->name('login.phone.form');
    Route::post('/login/phone/send-otp', [ĐNController::class, 'sendOtp'])->name('login.phone.send-otp');
    Route::get('/login/phone/verify', [ĐNController::class, 'verifyOtpForm'])->name('login.phone.verify');
    Route::post('/login/phone/verify', [ĐNController::class, 'verifyOtp'])->name('login.phone.verify.post');
    Route::get('/login/google', [SocialAuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// 3. AUTH ROUTES (CẦN ĐĂNG NHẬP)
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [ĐNController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    Route::get('/my-playlists', [PlaylistController::class, 'index'])->name('playlist.index');
    Route::get('/my-playlists/{id}', [PlaylistController::class, 'show'])->name('playlist.show');
    Route::post('/my-playlists/create', [PlaylistController::class, 'store'])->name('playlist.store');
    Route::delete('/my-playlists/{id}', [PlaylistController::class, 'destroy'])->name('playlist.destroy');
    Route::post('/my-playlists/{id}/update', [PlaylistController::class, 'update'])->name('playlist.update');
    Route::post('/my-playlists/{id}/visibility', [PlaylistController::class, 'updateVisibility'])->name('playlist.visibility');
    Route::post('/my-playlists/{id}/copy-songs', [PlaylistController::class, 'copySongsToPlaylist'])->name('playlist.copy-songs');
    Route::get('/my-playlists/{id}/search-songs', [PlaylistController::class, 'searchSongs'])->name('playlist.search-songs');
    Route::delete('/my-playlists/{id}/remove-song', [PlaylistController::class, 'removeSongFromPlaylist'])->name('playlist.remove-song');
    Route::post('/ajax/playlist/add-song', [PlaylistController::class, 'addSongToPlaylist'])->name('playlist.add-song');

    Route::get('/ajax/like-song/status', [InteractionController::class, 'likeSongStatus']);
    Route::post('/ajax/like-song', [InteractionController::class, 'toggleLikeSong']);
    Route::post('/ajax/like-artist', [InteractionController::class, 'toggleLikeArtist']);
    Route::post('/ajax/record-history', [InteractionController::class, 'recordHistory']);

    Route::get('/api/notifications', [NotificationApiController::class, 'getNotifications'])->name('api.notifications.get');
    Route::post('/api/notifications/{notificationId}/read', [NotificationApiController::class, 'markAsRead'])->name('api.notifications.read');
    Route::post('/api/notifications/read-all', [NotificationApiController::class, 'markAllAsRead'])->name('api.notifications.read-all');
});

// 4. KHU VỰC ADMIN (CẦN ĐĂNG NHẬP)
Route::prefix('admin')->middleware(['auth', 'active.user', 'admin.only'])->group(function () {
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

    Route::get('/albums', [AdminAlbumController::class, 'index'])->name('admin.albums.index');
    Route::get('/albums/create', [AdminAlbumController::class, 'create'])->name('admin.albums.create');
    Route::post('/albums', [AdminAlbumController::class, 'store'])->name('admin.albums.store');
    Route::get('/albums/{id}/edit', [AdminAlbumController::class, 'edit'])->name('admin.albums.edit');
    Route::put('/albums/{id}', [AdminAlbumController::class, 'update'])->name('admin.albums.update');
    Route::delete('/albums/{id}', [AdminAlbumController::class, 'destroy'])->name('admin.albums.destroy');

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

    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');
    Route::get('/notifications/{notification}/edit', [NotificationController::class, 'edit'])->name('admin.notifications.edit');
    Route::put('/notifications/{notification}', [NotificationController::class, 'update'])->name('admin.notifications.update');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::post('/notifications/{notification}/resend', [NotificationController::class, 'resend'])->name('admin.notifications.resend');
});
