<?php

namespace App\Http\Controllers\Institucional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('institucional.login');
    }

    public function login(Request $request)
    {
        $usuario = $request->input('usuario');
        $contrasena = $request->input('contrasena');

        if (Auth::attempt(['usuario' => $usuario, 'password' => $contrasena])) {
            $user = Auth::user();

            // Redirige según el rol
            if ($user->rol === 'administrador') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->rol === 'institucion') {
                return redirect()->route('institucional.dashboard');
            }
            // Por si hay otros tipos, agrega aquí más condiciones
            return redirect('/'); // Redirige a inicio si no es admin/institucion
        }

        return back()->with('error', 'Credenciales inválidas.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $institution = $user->institution;
        return view('institucional.dashboard', compact('user', 'institution'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('institucional.login');
    }

    public function showChangeForm()
    {
        return view('institucional.cambiar_contrasena');
    }

    public function changeCredentials(Request $request)
    {
        $request->validate([
            'usuario_nuevo' => 'required|string|max:255',
            'contrasena_actual' => 'required|string',
            'contrasena_nueva' => 'required|string|min:6',
            'confirmar_contrasena' => 'required|string|same:contrasena_nueva'
        ]);

        $user = Auth::user();

        // Verifica contraseña actual
        if (!Hash::check($request->contrasena_actual, $user->contrasena_hash)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        // Cambia usuario y contraseña
        $user->usuario = $request->usuario_nuevo;
        $user->contrasena_hash = Hash::make($request->contrasena_nueva);
        $user->save();

        return back()->with('success', 'Credenciales actualizadas exitosamente.');
    }
}
