<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'isLocked') && $user->isLocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'tài khoản đã bị khóa',
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'username' => 'tài khoản đã bị khóa',
            ]);
        }

        return $next($request);
    }
}
