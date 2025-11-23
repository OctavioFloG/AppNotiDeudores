<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CuentaPorCobrar;
use App\Models\Institution;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Mostrar la vista del dashboard
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * API - Obtener estadísticas del dashboard
     */
    public function dashboard(Request $request)
    {
        $user = auth('sanctum')->user();

        // Obtener estadísticas
        $institutionCount = Institution::count();
        $userCount = User::count();
        $debtCount = CuentaPorCobrar::where('estado', '!=', 'Pagada')->count();
        $notificationCount = Notificacion::count();
        
        // Monto total de deudas activas
        $totalDebtAmount = CuentaPorCobrar::where('estado', '!=', 'Pagada')->sum('monto') ?? 0;

        // Deudas vencidas
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
                'total_institutions' => $institutionCount,
                'total_clients' => $userCount,
                'active_debts' => $debtCount,
                'notifications_sent' => $notificationCount,
                'total_debt_amount' => $totalDebtAmount,
                'overdue_debts' => $overdueCount,
            ],
            'recent_debts' => $recentDebts,
        ]);
    }
}
