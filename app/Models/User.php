<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = true;
    protected $fillable = ['username', 'password', 'password_hash', 'email', 'phone', 'full_name', 'name', 'avatar_url', 'is_admin', 'status', 'registration_date', 'remember_token', 'google_id', 'reset_token'];
    protected $hidden = ['password', 'password_hash']; // Bảo mật mật khẩu

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
?>

