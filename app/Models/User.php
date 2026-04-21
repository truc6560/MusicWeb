<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'password',
        'password_hash',
        'email',
        'phone',
        'full_name',
        'name',
        'avatar_url',
        'is_admin',
        'status',
        'registration_date',
        'remember_token',
        'google_id',
        'reset_token',
    ];

    protected $hidden = ['password', 'password_hash', 'remember_token'];

    public function isLocked(): bool
    {
        $status = $this->status;

        if (is_int($status) || ctype_digit((string) $status)) {
            return (int) $status === 0;
        }

        if (is_bool($status)) {
            return $status === false;
        }

        $normalized = strtolower(trim((string) $status));

        if ($normalized === '') {
            return false;
        }

        return in_array($normalized, ['banned', 'locked', 'inactive', 'disabled', 'suspended', '0', 'false'], true);
    }

    public function getAuthPassword()
    {
        return $this->password_hash ?: $this->password;
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

