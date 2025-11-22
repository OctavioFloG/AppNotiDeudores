<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->rol === 'administrador') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->rol === 'institucion') {
                return redirect()->route('institution.dashboard');
            }
        }

        return $next($request);
    }
}
