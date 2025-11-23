<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CuentaPorCobrar;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Generar reporte de deudores
     * GET /api/institution/reporte-deudores
     */
    public function reporteDeudores(Request $request)
    {
        try {
            $institucionId = $request->user()->id_institucion;

            // Obtener filtros
            $estado = $request->query('estado');
            $fechaDesde = $request->query('fecha_desde');
            $fechaHasta = $request->query('fecha_hasta');

            // Query base
            $query = CuentaPorCobrar::where('id_institucion', $institucionId)
                ->with('client')
                ->orderBy('fecha_vencimiento', 'asc');

            // Filtrar por estado
            if ($estado) {
                $query->where('estado', ucfirst($estado));
            }

            // Filtrar por rango de fechas
            if ($fechaDesde) {
                $query->where('fecha_vencimiento', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $query->where('fecha_vencimiento', '<=', $fechaHasta);
            }

            $deudas = $query->get();

            // Calcular totales
            $totalDeudas = $deudas->count();
            $montoTotal = $deudas->sum('monto');
            $deudasVencidas = $deudas->where('estado', 'Vencida')->count();
            $montoVencido = $deudas->where('estado', 'Vencida')->sum('monto');
            $deudasPendientes = $deudas->where('estado', 'Pendiente')->count();
            $montoPendiente = $deudas->where('estado', 'Pendiente')->sum('monto');
            $deudasPagadas = $deudas->where('estado', 'Pagada')->count();
            $montoPagado = $deudas->where('estado', 'Pagada')->sum('monto');

            return response()->json([
                'success' => true,
                'data' => [
                    'deudas' => $deudas,
                    'resumen' => [
                        'total_deudas' => $totalDeudas,
                        'monto_total' => $montoTotal,
                        'deudas_vencidas' => $deudasVencidas,
                        'monto_vencido' => $montoVencido,
                        'deudas_pendientes' => $deudasPendientes,
                        'monto_pendiente' => $montoPendiente,
                        'deudas_pagadas' => $deudasPagadas,
                        'monto_pagado' => $montoPagado,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al generar reporte', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar reporte como CSV
     * GET /api/institution/reporte-deudores/exportar-csv
     */
    public function exportarCSV(Request $request)
    {
        try {
            $institucionId = $request->user()->id_institucion;
            $estado = $request->query('estado');
            $fechaDesde = $request->query('fecha_desde');
            $fechaHasta = $request->query('fecha_hasta');

            $query = CuentaPorCobrar::where('id_institucion', $institucionId)
                ->with('client')
                ->orderBy('fecha_vencimiento', 'asc');

            if ($estado) {
                $query->where('estado', ucfirst($estado));
            }
            if ($fechaDesde) {
                $query->where('fecha_vencimiento', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $query->where('fecha_vencimiento', '<=', $fechaHasta);
            }

            $deudas = $query->get();

            // Iniciar CSV con BOM para UTF-8
            $csv = chr(239) . chr(187) . chr(191); // BOM UTF-8
            $csv .= "ID,Cliente,Telefono,Email,Monto,Estado,Fecha Vencimiento,Descripcion\n";

            foreach ($deudas as $deuda) {
                $cliente = $deuda->client;

                // Convertir fecha a objeto DateTime si es string
                $fechaVencimiento = is_string($deuda->fecha_vencimiento)
                    ? \DateTime::createFromFormat('Y-m-d', $deuda->fecha_vencimiento)
                    : $deuda->fecha_vencimiento;

                $fechaFormato = $fechaVencimiento ? $fechaVencimiento->format('d/m/Y') : $deuda->fecha_vencimiento;

                // Escapar comillas en las descripciones
                $descripcion = str_replace('"', '""', $deuda->descripcion);
                $nombreCliente = str_replace('"', '""', $cliente->nombre);
                $telefono = $cliente->telefono;

                $csv .= sprintf(
                    "%d,\"%s\",\"'%s\",%s,%.2f,%s,\"%s\",\"%s\"\n",
                    $deuda->id_cuenta,
                    $nombreCliente,
                    $telefono,
                    $cliente->correo,
                    $deuda->monto,
                    $deuda->estado,
                    $fechaFormato,
                    $descripcion
                );
            }

            return response($csv, 200)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="reporte-deudores-' . date('d-m-Y-H-i-s') . '.csv"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        } catch (\Exception $e) {
            Log::error('Error al exportar CSV', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al exportar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
