<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as Log;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si no hay usuario, redirigir a login
        if (!$user) {
            return redirect('/login')->with('error', 'Debes iniciar sesión');
        }

        // Si el usuario tiene id_institucion, es institución (rechazar)
        if ($user->rol !== 'administrador') {
            //Destruir sesión
            Auth::logout();
            return redirect('/institution/dashboard')
                ->with('error', 'No tienes acceso a esta página');
        }

        return $next($request);
    }
}
