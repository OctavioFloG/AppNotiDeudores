<?php

namespace App\Jobs;

use App\Models\CuentaPorCobrar;
use App\Models\Notificacion;
use App\Services\WhatsurveyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnviarNotificacionVencida implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsurveyService $whatsurvey): void
    {
        $fechaObjetivo = now()->subDay()->toDateString();

        CuentaPorCobrar::whereDate('fecha_vencimiento', $fechaObjetivo)
            ->where('estado', 'Pendiente')
            ->with(['client', 'institution'])
            ->chunkById(100, function ($deudas) use ($whatsurvey) {
                foreach ($deudas as $deuda) {
                    if (!$deuda->client || !$deuda->client->telefono) {
                        continue;
                    }

                    $cliente  = $deuda->client;
                    $inst     = $deuda->institution ?? null;
                    $monto    = number_format($deuda->monto, 2, '.', ',');
                    $fechaVenc = \Carbon\Carbon::parse($deuda->fecha_vencimiento)->format('d/m/Y');
                    $nombreInst = $inst?->nombre ?? 'su institución';

                    $mensaje  = "¡Hola {$cliente->nombre}!\n\n";
                    $mensaje .= "Le informamos que su deuda ha vencido.\n";
                    $mensaje .= "Monto: \${$monto}\n";
                    $mensaje .= "Fecha de vencimiento: {$fechaVenc}\n\n";
                    $mensaje .= "Le invitamos a realizar el pago a la brevedad.\n";
                    $mensaje .= "Atentamente, {$nombreInst}.";

                    DB::beginTransaction();

                    try {
                        $resp = $whatsurvey->sendMessage($cliente->telefono, $mensaje);

                        $estado = $resp['success'] ? 'enviada' : 'fallida';

                        Notificacion::create([
                            'id_institucion' => $deuda->id_institucion,
                            'id_cliente'     => $cliente->id_cliente,
                            'id_cuenta'      => $deuda->id_cuenta,
                            'tipo'           => 'vencida',
                            'canal'          => 'whatsapp',
                            'asunto'         => 'Notificación de deuda vencida',
                            'mensaje'        => $mensaje,
                            'destinatario'   => $cliente->telefono,
                            'estado'         => $estado,
                            'fecha_envio'    => now(),
                        ]);

                        DB::commit();

                        if (!$resp['success']) {
                            Log::warning('Whatsurvey fallo deuda vencida', [
                                'deuda_id' => $deuda->id_cuenta,
                                'error'    => $resp['error'] ?? null,
                            ]);
                        }
                    } catch (\Throwable $e) {
                        DB::rollBack();

                        Log::error('Error job deuda vencida', [
                            'deuda_id' => $deuda->id_cuenta,
                            'error'    => $e->getMessage(),
                        ]);
                    }
                }
            });
    }
}
