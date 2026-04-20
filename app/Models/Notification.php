<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image_url', 'type', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function sendToAllUsers()
    {
        $userIds = User::pluck('user_id')->toArray();
        return $this->users()->attach($userIds);
    }

    public function sendToAdmins()
    {
        $adminIds = User::where('is_admin', 1)->pluck('user_id')->toArray();
        return $this->users()->attach($adminIds);
    }
}
