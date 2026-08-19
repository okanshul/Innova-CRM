<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Force2FASetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (setting('sec_req_2fa') == '1' || setting('sec_req_2fa') === true)) {
            if (isset($user->two_factor_secret) && !$user->two_factor_secret) {
                if (!$request->is('profile*') && !$request->is('logout')) {
                    return redirect()->route('profile.index')->with('warning', 'Two-Factor Authentication (2FA) is mandatory. Please configure 2FA on your profile.');
                }
            }
        }

        return $next($request);
    }
}
