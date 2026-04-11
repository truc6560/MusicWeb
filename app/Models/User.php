<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'password', 'email', 'full_name', 'avatar_url', 'is_admin', 'status'];
    protected $hidden = ['password']; // Bảo mật mật khẩu

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
}
?>

