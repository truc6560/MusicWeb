<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $primaryKey = 'song_id';
    protected $fillable = [
        'title', 'audio_file', 'image_url', 'release_date', 
        'duration', 'plays', 'genres', 'lyrics', 'artist_id', 'album_id'
    ];
    public $timestamps = false;
    // Bài hát thuộc về một nghệ sĩ
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    // Bài hát thuộc về một album (có thể null)
    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
?>