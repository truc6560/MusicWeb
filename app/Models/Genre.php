<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'genre_id';
    public $timestamps = false; // Bảng này thường không cần created_at
    protected $fillable = ['name', 'description'];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'song_genre', 'genre_id', 'song_id');
    }
}
?>