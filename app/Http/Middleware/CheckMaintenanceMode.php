<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (setting('maintenance_mode') == '1' || setting('maintenance_mode') === true) {
            // Allow login & logout routes
            if ($request->is('login') || $request->is('logout')) {
                return $next($request);
            }

            // Allow logged-in Administrator users
            $user = $request->user();
            if ($user && ($user->role === 'Administrator' || (method_exists($user, 'hasRole') && $user->hasRole('Administrator')))) {
                return $next($request);
            }

            if (view()->exists('errors.503')) {
                return response()->view('errors.503', [], 503);
            }

            return response('System is under maintenance. Please check back soon.', 503);
        }

        return $next($request);
    }
}
