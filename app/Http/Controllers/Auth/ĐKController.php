<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;

class ĐKController extends Controller
{
    // Hiển thị form đăng ký
    public function create()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function store(Request $request)
    {
        $request->validate([
        'username' => 'required|string|max:255|unique:users',
        'full_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => [
            'required',
            'confirmed',
            Password::min(8)           // Ít nhất 8 ký tự
                ->mixedCase()           // Có cả chữ hoa và thường
                ->letters()             // Có chữ cái
                ->numbers()             // Có số
                ->symbols()             // Có ký tự đặc biệt (tùy chọn)
        ],
    ]);

        $user = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'status' => 1,
            'is_admin' => 0,
            'registration_date' => now(),
        ]);

        event(new Registered($user));

        // Đăng nhập ngay sau khi đăng ký
        Auth::login($user);

        return redirect()->route('client.home');
    }
}
