<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model {
    protected $primaryKey = 'artist_id';
    protected $guarded = [];
    protected $table = 'artists'; // Phải có dòng này nếu database là 'artists'
    public $timestamps = false;
    // Một nghệ sĩ có nhiều bài hát
    public function songs() {
        return $this->hasMany(Song::class, 'artist_id');
    }
    
    // Một nghệ sĩ có nhiều album
    public function albums() {
        return $this->hasMany(Album::class, 'artist_id');
    }
}
?>