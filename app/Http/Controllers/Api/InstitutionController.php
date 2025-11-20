<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
    public function store(Request $request)
    {
        // Validación manual con mensaje personalizado
        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'telefono'  => 'required|string|max:50',
            'correo'    => 'required|email',
        ], [
            'correo.required' => 'El correo es requerido.',
            'correo.email'    => 'El correo no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validar correo duplicado manualmente
        $correoExiste = Institution::where('correo', $request->correo)->exists();
        if ($correoExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => [
                    'correo' => ['El correo ya está registrado en el sistema.']
                ]
            ], 422);
        }

        try {
            $validated = $validator->validated();
            
            // 1. Crear institución
            $institution = Institution::create($validated);

            // 2. Generar usuario y contraseña
            $username = strtolower(Str::slug($institution->nombre) . '-' . rand(1000, 9999));
            $password_raw = Str::random(12);

            // 3. Crear usuario admin
            $user = User::create([
                'id_institucion'  => $institution->id_institucion,
                'rol'             => 'administrador',
                'usuario'         => $username,
                'contrasena_hash' => Hash::make($password_raw),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Institución registrada exitosamente',
                'data' => [
                    'institution' => [
                        'id'         => $institution->id_institucion,
                        'nombre'     => $institution->nombre,
                        'correo'     => $institution->correo,
                        'telefono'   => $institution->telefono,
                        'direccion'  => $institution->direccion,
                        'created_at' => $institution->created_at,
                    ],
                    'admin_user' => [
                        'usuario'    => $user->usuario,
                        'contrasena' => $password_raw,
                    ],
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar institución',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
