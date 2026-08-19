<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $expiryDays = (int) setting('sec_password_expiry', 0);
            if ($expiryDays > 0 && isset($user->password_changed_at) && $user->password_changed_at) {
                if (now()->diffInDays($user->password_changed_at) >= $expiryDays) {
                    if (!$request->is('profile*') && !$request->is('logout')) {
                        return redirect()->route('profile.index')->with('warning', 'Your password has expired. Please update your password to continue.');
                    }
                }
            }
        }

        return $next($request);
    }
}
