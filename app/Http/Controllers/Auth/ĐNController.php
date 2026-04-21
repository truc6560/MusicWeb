<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ĐNController extends Controller
{
    private function lockedAccountErrorResponse(string $field = 'username')
    {
        return back()->withErrors([
            $field => 'tài khoản đã bị khóa',
        ])->onlyInput('username');
    }

    private function hasPhoneColumn(): bool
    {
        return Schema::hasColumn('users', 'phone');
    }

    private function phoneFeatureUnavailableResponse()
    {
        return back()->withErrors([
            'phone' => 'Tính năng đăng nhập bằng số điện thoại chưa sẵn sàng. Vui lòng chạy migrate để thêm cột phone.',
        ]);
    }

    private function phoneCandidates(string $rawPhone): array
    {
        $rawPhone = trim($rawPhone);
        $digitsOnly = preg_replace('/\D+/', '', $rawPhone) ?? '';

        $candidates = array_filter([
            $rawPhone,
            $digitsOnly,
            $digitsOnly !== '' ? '+' . $digitsOnly : null,
        ]);

        if (str_starts_with($digitsOnly, '84')) {
            $local = '0' . substr($digitsOnly, 2);
            $candidates[] = $local;
            $candidates[] = '+84' . substr($digitsOnly, 2);
        }

        if (str_starts_with($digitsOnly, '0')) {
            $international = '84' . substr($digitsOnly, 1);
            $candidates[] = $international;
            $candidates[] = '+' . $international;
        }

        return array_values(array_unique(array_filter($candidates)));
    }

    private function validateMailConfig(): ?string
    {
        if (config('mail.default') !== 'smtp') {
            return 'MAIL_MAILER hiện không phải smtp.';
        }

        if (!config('mail.mailers.smtp.host') || !config('mail.mailers.smtp.port')) {
            return 'Thiếu MAIL_HOST hoặc MAIL_PORT.';
        }

        if (!config('mail.mailers.smtp.username') || !config('mail.mailers.smtp.password')) {
            return 'Thiếu MAIL_USERNAME hoặc MAIL_PASSWORD.';
        }

        return null;
    }

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

        $identifier = trim($request->username);
        $candidateUsers = collect([
            User::where('username', $identifier)->first(),
            User::where('email', $identifier)->first(),
        ]);

        if ($this->hasPhoneColumn()) {
            foreach ($this->phoneCandidates($request->username) as $phone) {
                $candidateUsers->push(User::where('phone', $phone)->first());
            }
        }

        foreach ($candidateUsers->filter()->unique('user_id') as $user) {
            if (!Hash::check($request->password, $user->getAuthPassword())) {
                continue;
            }

            if ($user->isLocked()) {
                return $this->lockedAccountErrorResponse();
            }

            Auth::login($user);
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
        if (!$this->hasPhoneColumn()) {
            return $this->phoneFeatureUnavailableResponse();
        }

        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user = User::whereIn('phone', $this->phoneCandidates($request->phone))->first();

        if (!$user) {
            return back()->withErrors([
                'phone' => 'Không tìm thấy tài khoản với số điện thoại này.',
            ])->withInput();
        }

        if ($user->isLocked()) {
            return back()->withErrors([
                'phone' => 'tài khoản đã bị khóa',
            ])->withInput();
        }

        $phone = $user->phone;
        $otp = rand(100000, 999999); // Tạo OTP 6 chữ số

        // Lưu OTP vào session hoặc cache (đơn giản dùng session)
        session(['phone_otp' => $otp, 'phone' => $phone]);

        // Gửi OTP qua email (giả sử user có email liên kết với phone)
        if (!$user || !$user->email) {
            session()->forget(['phone_otp', 'phone']);
            return back()->withErrors([
                'phone' => 'Tài khoản này chưa có email để nhận OTP.',
            ]);
        }

        $mailConfigError = $this->validateMailConfig();
        if ($mailConfigError !== null) {
            session()->forget(['phone_otp', 'phone']);
            return back()->withErrors([
                'phone' => 'Cấu hình email chưa đầy đủ: ' . $mailConfigError,
            ])->withInput();
        }

        try {
            Mail::raw("Mã OTP của bạn là: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Mã OTP đăng nhập');
            });
        } catch (\Throwable $e) {
            Log::error('Send OTP email failed: ' . $e->getMessage());
            session()->forget(['phone_otp', 'phone']);
            $errorMessage = 'Không thể gửi OTP lúc này. Vui lòng kiểm tra cấu hình email và thử lại.';
            if (app()->environment('local')) {
                $errorMessage .= ' Chi tiết: ' . $e->getMessage();
            }
            return back()->withErrors([
                'phone' => $errorMessage,
            ])->withInput();
        }

        return redirect()->route('login.phone.verify')->with('success', 'OTP đã được gửi đến email của bạn.');
    }

    public function verifyOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        if (!$this->hasPhoneColumn()) {
            return $this->phoneFeatureUnavailableResponse();
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (session('phone_otp') == $request->otp) {
            $user = User::where('phone', session('phone'))->first();
            if (!$user) {
                session()->forget(['phone_otp', 'phone']);
                return back()->withErrors(['otp' => 'Phiên OTP không hợp lệ, vui lòng gửi lại mã.']);
            }

            if ($user->isLocked()) {
                session()->forget(['phone_otp', 'phone']);
                return back()->withErrors(['otp' => 'tài khoản đã bị khóa']);
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