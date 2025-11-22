<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Si ya está autenticado, ir al dashboard correspondiente
        $user = auth('sanctum')->user();
        
        if ($user) {
            // Si tiene id_institucion, es una institución
            if ($user->id_institucion) {
                return redirect('/institution/dashboard');
            }
            // Si no tiene id_institucion, es admin
            return redirect('/admin/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario por email
        $user = User::where('email', $credentials['email'])->first();

        // Validar que exista y la contraseña sea correcta
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])->onlyInput('email');
        }

        // Crear token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retornar respuesta con token y redirect
        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'token' => $token,
                'user' => $user
            ],
            'redirect_url' => $user->id_institucion ? '/institution/dashboard' : '/admin/dashboard'
        ]);
    }

    public function logout(Request $request)
    {
        // Revocar todos los tokens del usuario
        $request->user('sanctum')?->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
