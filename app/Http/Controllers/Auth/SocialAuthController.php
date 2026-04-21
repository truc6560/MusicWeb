<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected function googleProvider()
    {
        $redirectUrl = config('services.google.redirect') ?: route('login.google.callback');

        $provider = Socialite::driver('google')->stateless()
            ->redirectUrl($redirectUrl);

        if (app()->environment('local')) {
            $provider->setHttpClient(new Client(['verify' => false]));
        }

        return $provider;
    }

    public function redirectToGoogle(Request $request)
    {
        return $this->googleProvider()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = $this->googleProvider()->user();
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Không thể đăng nhập bằng Google: ' . $e->getMessage()]);
        }

        // Tìm user theo google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Tìm theo email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Cập nhật google_id cho user cũ
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // Tạo user mới tự động
                $generatedPassword = Hash::make(Str::random(16));

                try {
                    $user = User::create([
                        'username' => $this->generateUniqueUsername($googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail()),
                        'full_name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Người dùng Google',
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password_hash' => $generatedPassword,
                        'avatar_url' => $googleUser->getAvatar(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('User creation failed: ' . $e->getMessage());
                    return redirect()->route('login')->withErrors(['error' => 'Không thể tạo tài khoản: ' . $e->getMessage()]);
                }
            }
        }

        if (!$user) {
            Log::error('User not found or created');
            return redirect()->route('login')->withErrors(['error' => 'Không thể tìm thấy hoặc tạo tài khoản']);
        }

        if ($user->isLocked()) {
            return redirect()->route('login')->withErrors(['username' => 'tài khoản đã bị khóa']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Debug: kiểm tra user đã login chưa
        if (Auth::check()) {
            Log::info('User logged in successfully: ' . Auth::user()->username);
            return redirect('/');
        } else {
            Log::error('Auth::login failed for user: ' . $user->username);
            return redirect()->route('login')->withErrors(['error' => 'Đăng nhập thất bại']);
        }
    }

    protected function generateUniqueUsername($base)
    {
        $username = Str::slug($base, '_');
        $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
        $username = substr($username, 0, 24);

        if (!$username) {
            $username = 'google_user';
        }

        $original = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $original . '_' . $counter++;
        }

        return $username;
    }

}