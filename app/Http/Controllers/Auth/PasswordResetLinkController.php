<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);
        
        $user->reset_token = $token;
        $user->save();

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        try {
            Mail::send('mail.reset-mail', [
                'name' => $user->full_name ?? $user->username,
                'resetLink' => $resetLink
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Đặt lại mật khẩu - MusicWeb');
            });

            return back()->with('status', 'Link đặt lại mật khẩu đã được gửi đến email của bạn!');
            
        } catch (\Exception $e) {
            // Log lỗi để xem chi tiết
            \Log::error('Mail error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Có lỗi khi gửi email. Lỗi: ' . $e->getMessage()]);
        }
    }
}