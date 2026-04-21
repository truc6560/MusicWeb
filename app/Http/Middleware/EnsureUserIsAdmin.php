<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || (int) ($user->is_admin ?? 0) !== 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn không có quyền truy cập khu vực admin.',
                ], 403);
            }

            return redirect()->route('client.home')->withErrors([
                'username' => 'Bạn không có quyền truy cập khu vực admin.',
            ]);
        }

        return $next($request);
    }
}
