<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('contrasena')
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->rol === 'administrador') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->rol === 'institucion') {
                return redirect()->route('institution.dashboard');
            }
            return redirect('/');
        }

        return back()->with('error', 'Credenciales inválidas.');
    }


    public function dashboard()
    {
        $user = Auth::user();
        $institution = $user->institution;
        return view('institution.dashboard', compact('user', 'institution'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('institution.login');
    }

    public function showChangeForm()
    {
        return view('institution.cambiar_contrasena');
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
