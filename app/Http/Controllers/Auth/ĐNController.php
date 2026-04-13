<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ĐNController extends Controller
{
    // Hiển thị form đăng nhập
    public function create()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Thử đăng nhập với username
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('client.home'));
        }

        // Nếu không tìm thấy username, thử với email
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('client.home'));
        }

        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không đúng.',
        ])->onlyInput('username');
    }

    // Xử lý đăng xuất
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}