<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class NewPasswordController extends Controller
{
    public function create($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Tìm user theo email và reset_token
        $user = User::where('email', $request->email)
                    ->where('reset_token', $request->token)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Cập nhật mật khẩu mới và xóa reset_token
        $user->password_hash = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Mật khẩu đã được đặt lại! Vui lòng đăng nhập.');
    }
}