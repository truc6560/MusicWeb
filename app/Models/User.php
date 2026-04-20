<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'full_name',
        'avatar_url',
        'is_admin',
        'status',
        'registration_date',
    ];
    protected $hidden = ['password_hash']; // Bảo mật mật khẩu

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function likedSongs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'favorite_songs', 'user_id', 'song_id');
    }

    public function likedArtists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'favorite_artists', 'user_id', 'artist_id');
    }

    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class, 'user_id', 'user_id');
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_user', 'user_id', 'notification_id')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->wherePivot('is_read', false)->orderByPivot('created_at', 'desc');
    }
}

