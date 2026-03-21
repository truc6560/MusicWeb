<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'genre_id';
    public $timestamps = false; // Bảng này thường không cần created_at
    protected $fillable = ['name', 'description'];
}
?>