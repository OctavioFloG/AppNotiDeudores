<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CuentaPorCobrar;
use App\Models\Institution;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function dashboard(Request $request)
    {
        $user = auth('sanctum')->user();

        // Obtener estadísticas
        $institutionCount = Institution::count();
        $userCount = User::count();
        $debtCount = CuentaPorCobrar::count();
        $overdueCount = CuentaPorCobrar::where('fecha_vencimiento', '<', now())
                            ->where('estado', '!=', 'Pagada')
                            ->count();

        // Deudas recientes
        $recentDebts = CuentaPorCobrar::with('institution', 'client')
                          ->latest()
                          ->limit(5)
                          ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard cargado exitosamente',
            'user' => $user,
            'stats' => [
                'institutions' => $institutionCount,
                'users' => $userCount,
                'debts' => $debtCount,
                'overdue' => $overdueCount,
            ],
            'recent_debts' => $recentDebts,
        ]);
    }
}
