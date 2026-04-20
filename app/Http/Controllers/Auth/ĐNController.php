<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            return redirect()->intended('/');
        }

        // Nếu không tìm thấy username, thử với email
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Nếu không tìm thấy username hoặc email, thử với số điện thoại
        $credentials = [
            'phone' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không đúng.',
        ])->onlyInput('username');
    }

    public function phoneForm()
    {
        return view('auth.login-phone');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'exists:users,phone'],
        ]);

        $phone = $request->phone;
        $otp = rand(100000, 999999); // Tạo OTP 6 chữ số

        // Lưu OTP vào session hoặc cache (đơn giản dùng session)
        session(['phone_otp' => $otp, 'phone' => $phone]);

        // Gửi OTP qua email (giả sử user có email liên kết với phone)
        $user = User::where('phone', $phone)->first();
        if (!$user || !$user->email) {
            session()->forget(['phone_otp', 'phone']);
            return back()->withErrors([
                'phone' => 'Tài khoản này chưa có email để nhận OTP.',
            ]);
        }

        Mail::raw("Mã OTP của bạn là: $otp", function ($message) use ($user) {
            $message->to($user->email)->subject('Mã OTP đăng nhập');
        });

        return redirect()->route('login.phone.verify')->with('success', 'OTP đã được gửi đến email của bạn.');
    }

    public function verifyOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (session('phone_otp') == $request->otp) {
            $user = User::where('phone', session('phone'))->first();
            if (!$user) {
                session()->forget(['phone_otp', 'phone']);
                return back()->withErrors(['otp' => 'Phiên OTP không hợp lệ, vui lòng gửi lại mã.']);
            }

            Auth::login($user);
            session()->forget(['phone_otp', 'phone']);
            return redirect()->intended('/');
        }

        return back()->withErrors(['otp' => 'Mã OTP không đúng.']);
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