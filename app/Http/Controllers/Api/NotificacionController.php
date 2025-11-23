<?php

namespace App\Http\Controllers\Api;

use App\Models\Notificacion;
use App\Models\CuentaPorCobrar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificacionController extends Controller
{
    /**
     * Crear y enviar notificación
     */
    public function enviar(Request $request)
    {
        $request->validate([
            'id_cuenta' => 'required|exists:cuentas_por_cobrar,id_cuenta',
            'tipo' => 'required|in:email,sms,whatsapp,push',
            'mensaje' => 'required|string',
            'asunto' => 'nullable|string',
            'destinatario' => 'required|string', // Email o teléfono
        ]);

        try {
            // Obtener la deuda
            $cuenta = CuentaPorCobrar::with(['client', 'institution'])->find($request->id_cuenta);

            if (!$cuenta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Deuda no encontrada'
                ], 404);
            }

            // Crear notificación
            $notificacion = Notificacion::create([
                'id_institucion' => $cuenta->id_institucion,
                'id_cliente' => $cuenta->id_cliente,
                'id_cuenta' => $request->id_cuenta,
                'tipo' => $request->tipo,
                'mensaje' => $request->mensaje,
                'asunto' => $request->asunto,
                'destinatario' => $request->destinatario,
                'estado' => 'pendiente'
            ]);

            // Enviar según el tipo
            $enviada = $this->enviarNotificacion($notificacion);

            // Actualizar estado
            if ($enviada) {
                $notificacion->update([
                    'estado' => 'enviada',
                    'fecha_envio' => now()
                ]);
            } else {
                $notificacion->update(['estado' => 'fallida']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notificación ' . ($enviada ? 'enviada' : 'guardada (no enviada)'),
                'data' => $notificacion
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la notificación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar notificación por diferentes canales
     */
    private function enviarNotificacion(Notificacion $notificacion)
    {
        try {
            switch ($notificacion->tipo) {
                case 'email':
                    return $this->enviarEmail($notificacion);
                case 'sms':
                    return $this->enviarSMS($notificacion);
                case 'whatsapp':
                    return $this->enviarWhatsApp($notificacion);
                case 'push':
                    return $this->enviarPush($notificacion);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación: ' . $e->getMessage());
            $notificacion->update(['respuesta_error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Enviar por Email
     */
    private function enviarEmail(Notificacion $notificacion)
    {
        try {
            // Aquí implementa tu lógica de email
            // Ejemplo con Mail::send() o Mailable
            Log::info('Email enviado a: ' . $notificacion->destinatario);
            return true;
        } catch (\Exception $e) {
            Log::error('Error email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar por SMS
     */
    private function enviarSMS(Notificacion $notificacion)
    {
        try {
            // Aquí implementa tu servicio SMS (Twilio, Nexmo, etc.)
            Log::info('SMS enviado a: ' . $notificacion->destinatario);
            return true;
        } catch (\Exception $e) {
            Log::error('Error SMS: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar por WhatsApp
     */
    private function enviarWhatsApp(Notificacion $notificacion)
    {
        try {
            // Aquí implementa tu servicio WhatsApp (Twilio, Baileys, etc.)
            Log::info('WhatsApp enviado a: ' . $notificacion->destinatario);
            return true;
        } catch (\Exception $e) {
            Log::error('Error WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar Push Notification
     */
    private function enviarPush(Notificacion $notificacion)
    {
        try {
            // Aquí implementa tu servicio Push (FCM, OneSignal, etc.)
            Log::info('Push enviado');
            return true;
        } catch (\Exception $e) {
            Log::error('Error Push: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener notificaciones de una deuda
     */
    public function obtenerPorDeuda($id_cuenta)
    {
        $notificaciones = Notificacion::where('id_cuenta', $id_cuenta)
            ->with(['cuentaPorCobrar', 'institucion'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }

    /**
     * Obtener notificaciones de una institución
     */
    public function obtenerPorInstitucion($id_institucion)
    {
        $notificaciones = Notificacion::where('id_institucion', $id_institucion)
            ->with(['cuentaPorCobrar', 'cliente'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }

    /**
     * Listar todas las notificaciones
     */
    public function listar(Request $request)
    {
        $institucionId = $request->user()->id_institucion;
        $notificaciones = Notificacion::with('cliente')
            ->where('id_institucion', $institucionId)
            ->orderBy('fecha_envio', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notificaciones->map(function ($n) {
                return [
                    'fecha' => $n->fecha_envio,
                    'cliente' => $n->cliente->nombre,
                    'tipo' => $n->tipo,
                    'estado' => $n->estado,
                    'mensaje' => $n->mensaje,
                ];
            })
        ]);
    }

    /**
     * Marcar como leída
     */
    public function marcarComoLeida($id_notificacion)
    {
        $notificacion = Notificacion::find($id_notificacion);

        if (!$notificacion) {
            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $notificacion->update(['estado' => 'leida']);

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída'
        ]);
    }

    /**
     * Eliminar notificación
     */
    public function eliminar($id_notificacion)
    {
        $notificacion = Notificacion::find($id_notificacion);

        if (!$notificacion) {
            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada'
        ]);
    }
}
