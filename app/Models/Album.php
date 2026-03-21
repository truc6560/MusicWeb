<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $primaryKey = 'album_id';
    protected $fillable = ['title', 'image_url', 'release_year', 'description', 'artist_id'];
    public $timestamps = false;
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'album_id');
    }
}
?>
