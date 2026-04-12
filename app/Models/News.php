<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $primaryKey = 'news_id';
    public $timestamps = false;
    protected $fillable = ['title', 'image_url', 'content', 'post_date'];
}
?>