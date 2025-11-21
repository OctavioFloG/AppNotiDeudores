<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function create()
    {
        return view('admin.institutions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|unique:institutions,correo',
            'telefono' => 'required|string|max:20'
        ]);

        // Crear institución
        $institution = Institution::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'correo' => $request->correo,
            'telefono' => $request->telefono
        ]);

        // Generar usuario y contraseña automáticamente
        $usuario = strtolower(str_replace(' ', '_', $request->nombre)) . '_' . Str::random(4);
        $contrasena = Str::random(10);

        // Crear usuario para la institución
        $user = User::create([
            'id_institucion' => $institution->id_institucion,
            'usuario' => $usuario,
            'contrasena_hash' => Hash::make($contrasena),
            'rol' => 'institucion'
        ]);

        return redirect()->route('admin.institutions.index')
            ->with('success', "Institución registrada. Usuario: $usuario | Contraseña: $contrasena");
    }

    public function index()
    {
        $institutions = Institution::all();
        return view('admin.institutions.index', compact('institutions'));
    }
}
