<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CuentaPorCobrar;
use Illuminate\Http\Request;

class InstitutionDashboardController extends Controller

{
    public function index()
    {
        return view('institution.dashboard');
    }

    public function dashboard(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            // Escribir en consola del navegador
            error_log('Usuario autenticado: ' . json_encode($user));

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            $institutionId = $user->id_institucion;

            if (!$institutionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario sin institución asignada'
                ], 400);
            }

            // Contar clientes
            $clientCount = Client::where('id_institucion', $institutionId)->count();

            // Contar deudas
            $debtCount = CuentaPorCobrar::where('id_institucion', $institutionId)->count();

            // Deudas pendientes
            $pendingCount = CuentaPorCobrar::where('id_institucion', $institutionId)
                ->where('estado', '!=', 'Pagada')
                ->count();

            // Deudas vencidas
            $overdueCount = CuentaPorCobrar::where('id_institucion', $institutionId)
                ->where('fecha_vencimiento', '<', now())
                ->where('estado', '=', 'Vencida')
                ->count();

            // Clientes recientes con sus deudas
            $recentClients = Client::where('id_institucion', $institutionId)
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($client) {
                    $debts = CuentaPorCobrar::where('id_cliente', $client->id_cliente)->get();
                    return [
                        'id' => $client->id_cliente,
                        'nombre' => $client->nombre,
                        'telefono' => $client->telefono,
                        'correo' => $client->correo,
                        'deuda_count' => $debts->count(),
                        'deuda_total' => $debts->sum('monto') ?? 0
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Dashboard Institucional',
                'stats' => [
                    'clients' => $clientCount,
                    'debts' => $debtCount,
                    'pending' => $pendingCount,
                    'overdue' => $overdueCount
                ],
                'recent_clients' => $recentClients,
                'data' => [
                    'status' => 'ok',
                    'institution_id' => $institutionId
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    public function crearCliente()
    {
        return view('institution.clients.create');
    }

    public function clientes()
    {
        return view('institution.clients.index');
    }

    public function verCliente($id)
    {
        return view('institution.clients.show', ['clientId' => $id]);
    }

    public function crearDeuda()
    {
        return view('institution.deudas.create');
    }

    public function deudas()
    {
        return view('institution.deudas.index');
    }

    public function notificaciones()
    {
        return view('institution.notificaciones');
    }
}
