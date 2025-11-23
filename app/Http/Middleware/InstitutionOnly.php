<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstitutionOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        
        Log::info('InstitutionOnly Middleware', [
            'user_id' => $user ? $user->id_usuario : 'NO USER',
            'usuario' => $user ? $user->usuario : null,
            'rol' => $user ? $user->rol : null,
            'id_institucion' => $user ? $user->id_institucion : null,
            'path' => $request->path()
        ]);

        if (!$user) {
            Log::error('InstitutionOnly: No user authenticated');
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // ✅ Verificar que sea institución (tenga id_institucion)
        if (!$user->id_institucion) {
            Log::warning('InstitutionOnly: User is not institution', [
                'user_id' => $user->id_usuario,
                'rol' => $user->rol
            ]);
            return response()->json(['error' => 'No tienes acceso de institución'], 403);
        }

        Log::info('InstitutionOnly: Access granted to institution', [
            'user_id' => $user->id_usuario,
            'id_institucion' => $user->id_institucion
        ]);
        return $next($request);
    }
}
