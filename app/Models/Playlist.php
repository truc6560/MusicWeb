<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $primaryKey = 'playlist_id';
    protected $fillable = ['name', 'user_id', 'is_public'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public $timestamps = false;
    public function songs()
    {
        return $this->belongsToMany(Song::class, 'playlist_songs', 'playlist_id', 'song_id');
    }
}
?>