<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Institution;
use App\Models\User;
use App\Models\CuentaPorCobrar;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->rol !== 'administrador') {
            abort(403, 'Acceso prohibido');
        }

        // Estadísticas
        $stats = [
            'instituciones' => Institution::count(),
            'usuarios' => User::count(),
            'deudas' => CuentaPorCobrar::count(),
            'deudas_pendientes' => CuentaPorCobrar::where('estado', 'Pendiente')->count(),
            'deudas_vencidas' => CuentaPorCobrar::where('estado', 'Vencida')->count(),
            'deudas_pagadas' => CuentaPorCobrar::where('estado', 'Pagada')->count()
        ];

        $institution = $user->institution ?? null;

        return view('admin.dashboard', compact('user', 'institution', 'stats'));
    }
}
