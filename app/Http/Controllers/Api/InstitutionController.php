<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function store(Request $request)
    {
        // Solo admins pueden registrar instituciones
        if ($request->user()->rol !== 'administrador') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para registrar instituciones'
            ], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:institutions,nombre',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:institutions,correo',
            'telefono' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Crear institución
            $institution = Institution::create([
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'correo' => $request->correo,
                'telefono' => $request->telefono
            ]);

            // Generar credenciales automáticamente
            $usuario = strtolower(str_replace(' ', '_', $request->nombre)) . '_' . Str::random(4);
            $contrasena = Str::random(12);

            // Crear usuario para la institución
            $user = \App\Models\User::create([
                'id_institucion' => $institution->id_institucion,
                'usuario' => $usuario,
                'contrasena_hash' => Hash::make($contrasena),
                'rol' => 'institucion'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Institución registrada exitosamente',
                'data' => [
                    'institution' => [
                        'id' => $institution->id_institucion,
                        'nombre' => $institution->nombre,
                        'correo' => $institution->correo,
                        'telefono' => $institution->telefono,
                        'direccion' => $institution->direccion
                    ],
                    'credentials' => [
                        'usuario' => $usuario,
                        'contrasena' => $contrasena,
                        'message' => 'Guarda estas credenciales. No se mostrarán de nuevo.'
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la institución: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        if ($request->user()->rol !== 'administrador') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $institutions = Institution::all();
        return response()->json([
            'success' => true,
            'data' => $institutions
        ]);
    }
}
