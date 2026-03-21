<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'password', 'email', 'full_name', 'avatar_url', 'is_admin', 'status'];
    protected $hidden = ['password']; // Bảo mật mật khẩu
}
?>

