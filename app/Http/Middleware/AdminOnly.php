<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return redirect('/login');
        }

        // Si el usuario tiene id_institucion, es una institución, no admin
        if ($user->id_institucion) {
            return redirect('/institution/dashboard')->with('error', 'No tienes acceso a esta página');
        }

        return $next($request);
    }
}
