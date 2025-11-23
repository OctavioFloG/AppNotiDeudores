<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = null;

        // 1️⃣ Intentar obtener token del header Authorization
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            if (strpos($authHeader, 'Bearer ') === 0) {
                $token = substr($authHeader, 7);
            }
        }

        // 2️⃣ Si no hay en header, intentar de cookie
        if (!$token && $request->hasCookie('api_token')) {
            $token = $request->cookie('api_token');
        }

        // 3️⃣ Si no hay en cookie, intentar de query parameter (último recurso)
        if (!$token && $request->has('token')) {
            $token = $request->query('token');
        }

        Log::info('AuthToken middleware', [
            'has_token' => $token ? true : false,
            'from' => $token ? ($request->hasHeader('Authorization') ? 'header' : ($request->hasCookie('api_token') ? 'cookie' : 'query')) : 'none',
            'path' => $request->path()
        ]);

        // Laravel Sanctum procesará el token automáticamente
        return $next($request);
    }
}
