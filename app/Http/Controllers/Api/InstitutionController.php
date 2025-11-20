<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    /**
     * Registrar una nueva institución y crear usuario admin automáticamente
     */
    public function store(Request $request)
    {
        // Validación según criterios de aceptación de US1
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'telefono'  => 'required|string|max:50',
            'correo'    => 'required|email|unique:institutions,correo',
        ], [
            'correo.unique' => 'El correo ya está registrado en el sistema.',
            'correo.email'  => 'El correo no es válido.',
            'correo.required' => 'El correo es requerido.',
        ]);

        try {
            // 1. Crear la institución
            $institution = Institution::create($validated);

            // 2. Generar usuario y contraseña automáticamente
            $username = strtolower(Str::slug($institution->nombre) . '-' . rand(1000, 9999));
            $password_raw = Str::random(12);

            // 3. Crear usuario administrador asociado
            $user = User::create([
                'id_institucion'  => $institution->id_institucion,
                'rol'             => 'administrador',
                'usuario'         => $username,
                'contrasena_hash' => Hash::make($password_raw),
            ]);

            // 4. Retornar respuesta exitosa
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
