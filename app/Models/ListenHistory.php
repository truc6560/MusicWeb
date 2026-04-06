<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListenHistory extends Model
{
    protected $table = 'listen_history';
    public $timestamps = false;
    protected $fillable = ['user_id', 'song_id', 'listened_at'];

    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id');
    }
}
?>