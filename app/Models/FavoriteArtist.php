<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteArtist extends Model
{
    protected $table = 'favorite_artists';
    public $timestamps = false;
    protected $fillable = ['user_id', 'artist_id'];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }
}
?>