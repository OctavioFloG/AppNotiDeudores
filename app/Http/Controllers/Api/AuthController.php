<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login de institución (usuario admin)
     */
    public function login(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'usuario'    => 'required|string',
            'contrasena' => 'required|string',
        ], [
            'usuario.required'    => 'El usuario es requerido.',
            'contrasena.required' => 'La contraseña es requerida.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar usuario
        $user = User::where('usuario', $request->usuario)->first();

        // Validar que exista y que la contraseña sea correcta
        if (!$user || !Hash::check($request->contrasena, $user->contrasena_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas. Usuario o contraseña incorrectos.',
            ], 401);
        }

        // Generar token API con Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Obtener datos de la institución
        $institution = $user->institution;

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada exitosamente',
            'data' => [
                'user' => [
                    'id'              => $user->id_usuario,
                    'usuario'         => $user->usuario,
                    'rol'             => $user->rol,
                    'id_institucion'  => $user->id_institucion,
                ],
                'institution' => [
                    'id'        => $institution->id_institucion,
                    'nombre'    => $institution->nombre,
                    'correo'    => $institution->correo,
                    'telefono'  => $institution->telefono,
                    'direccion' => $institution->direccion,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 200);
    }

    /**
     * Logout - Revocar token
     */
    public function logout(Request $request)
    {
        try {
            // Obtener el token actual del usuario
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Cambiar contraseña (criterio: cambiar por defecto)
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contrasena_actual' => 'required|string',
            'contrasena_nueva'  => 'required|string|min:8|confirmed',
        ], [
            'contrasena_nueva.confirmed' => 'Las contraseñas no coinciden.',
            'contrasena_nueva.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Validar contraseña actual
        if (!Hash::check($request->contrasena_actual, $user->contrasena_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ], 401);
        }

        // Actualizar contraseña
        $user->update([
            'contrasena_hash' => Hash::make($request->contrasena_nueva)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ], 200);
    }

    /**
     * Obtener datos del usuario autenticado
     */
    public function me(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }
        
        $institution = $user->institution;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id'              => $user->id_usuario,
                    'usuario'         => $user->usuario,
                    'rol'             => $user->rol,
                    'id_institucion'  => $user->id_institucion,
                ],
                'institution' => [
                    'id'        => $institution->id_institucion,
                    'nombre'    => $institution->nombre,
                    'correo'    => $institution->correo,
                    'telefono'  => $institution->telefono,
                    'direccion' => $institution->direccion,
                ]
            ]
        ], 200);
    }
}
