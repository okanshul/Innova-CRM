<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Update last_active_at if null or > 2 minutes ago
            if (!$user->last_active_at || $user->last_active_at->diffInMinutes(now()) >= 2) {
                $user->forceFill(['last_active_at' => now()])->save();
            }
        }

        return $next($request);
    }
}
