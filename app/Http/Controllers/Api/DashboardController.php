<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // Aquí puedes retornar los datos relevantes del dashboard institucional
        return response()->json([
            'success' => true,
            'message' => 'Dashboard cargado correctamente',
            'data' => [
                'usuario' => $user->usuario,
                'rol' => $user->rol,
                'id_institucion' => $user->id_institucion
            ]
        ]);
    }
}
