<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        Log::info('Middleware RedirectIfAuthenticatedToDashboard invoked.');
        Log::info('Current guard: sanctum');
        Log::info('Is authenticated: ' . (Auth::guard('sanctum')->check() ? 'true' : 'false'));
        if (Auth::guard('sanctum')->check()) {
            Log::info('User is authenticated. Checking role for redirection.');
            $user = Auth::guard('sanctum')->user();
            Log::info('Authenticated user ID: ' . $user->id_usuario . ', Role: ' . $user->rol);
            Log::info('User role: ' . $user->rol);
            if ($user && isset($user->rol)) {
                if ($user->rol === 'administrador') {
                    return redirect()->route('admin.dashboard');
                }
                if ($user->rol === 'institucion') {
                    return redirect()->route('institution.dashboard');
                }
            }
        }
        Log::info('User not authenticated or no redirect rule matched. Proceeding to next middleware/controller.');

        return $next($request);
    }
}
