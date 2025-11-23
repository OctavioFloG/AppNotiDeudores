<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CuentaPorCobrar;
use App\Models\Notificacion;
use App\Models\ClientToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DebtNotificationController extends Controller
{
    /**
     * Enviar notificación de deudas con link directo
     * POST /api/enviar-deudas-link/{id_cliente}
     */
    public function enviarDeudaConLink(Request $request, $id_cliente)
    {
        try {
            $client = Client::where('id_cliente', $id_cliente)
                ->where('id_institucion', auth('sanctum')->user()->id_institucion)
                ->firstOrFail();

            // Obtener deudas del cliente
            $deudas = CuentaPorCobrar::where('id_cliente', $id_cliente)
                ->where('estado', '!=', 'Pagada')
                ->get();

            if ($deudas->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cliente no tiene deudas pendientes'
                ], 400);
            }

            // Usar tabla client_tokens existente
            $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = now()->addMinutes(5);

            // Crear token en client_tokens
            $clientToken = ClientToken::create([
                'id_cliente' => $id_cliente,
                'token' => $token,
                'expires_at' => $expiresAt,
                'used' => false,
            ]);

            // Construir el link usando el token
            $debtLink = route('client.deudas.link', ['token' => $token]);

            // Información de deudas para el mensaje
            $totalDeudas = $deudas->count();
            $montoTotal = $deudas->sum('monto');

            // Mensaje formateado
            $mensaje = "¡Hola {$client->nombre}!\n\n";
            $mensaje .= "Tienes *{$totalDeudas}* deuda(s) pendiente(s) por un total de *\${$montoTotal}*\n\n";
            $mensaje .= "📱 Ver mis deudas:\n{$debtLink}\n\n";
            $mensaje .= "Este link expira en 5 minutos.";

            // Enviar por WhatsApp
            $respuestaWhatsurvey = $this->sendWhatsAppViaWhatsurvey(
                $client->telefono,
                $mensaje
            );

            if (!$respuestaWhatsurvey['success']) {
                Log::warning('Error al enviar WhatsApp con link de deudas', [
                    'cliente' => $client->nombre,
                    'telefono' => $client->telefono,
                    'error' => $respuestaWhatsurvey['error']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar notificación',
                    'error' => $respuestaWhatsurvey['error']
                ], 500);
            }

            // Guardar notificación
            $notificacion = Notificacion::create([
                'id_institucion' => $client->id_institucion,
                'id_cliente' => $id_cliente,
                'tipo' => 'whatsapp',
                'mensaje' => $mensaje,
                'asunto' => 'Notificación de deudas pendientes',
                'destinatario' => $client->telefono,
                'estado' => 'enviada',
                'fecha_envio' => now()
            ]);

            Log::info('Notificación de deudas enviada', [
                'cliente_id' => $id_cliente,
                'notificacion_id' => $notificacion->id_notificacion,
                'token_id' => $clientToken->id_token,
                'total_deudas' => $totalDeudas,
                'monto_total' => $montoTotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notificación de deudas enviada exitosamente',
                'data' => [
                    'notificacion_id' => $notificacion->id_notificacion,
                    'token_id' => $clientToken->id_token,
                    'cliente_id' => $id_cliente,
                    'cliente_nombre' => $client->nombre,
                    'total_deudas' => $totalDeudas,
                    'monto_total' => $montoTotal,
                    'link' => $debtLink,
                    'expires_at' => $expiresAt->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al enviar deudas con link', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar deudas con token automático
     * GET /cliente/deudas/{token}
     * 
     * Ruta sin autenticación para mostrar deudas directamente
     */
    public function mostrarDeudaConToken($token)
    {
        try {
            // Usar ClientToken model
            $clientToken = ClientToken::where('token', $token)
                ->where('expires_at', '>', now())
                ->where('used', false)
                ->first();

            if (!$clientToken) {
                Log::warning('Intento de acceso con token inválido', ['token' => substr($token, 0, 5) . '...']);
                
                return view('client.deudas-expirado', [
                    'mensaje' => 'El link ha expirado o es inválido'
                ]);
            }

            // Obtener cliente y sus deudas
            $client = Client::find($clientToken->id_cliente);
            
            if (!$client) {
                return view('client.deudas-expirado', [
                    'mensaje' => 'Cliente no encontrado'
                ]);
            }

            $deudas = CuentaPorCobrar::where('id_cliente', $clientToken->id_cliente)
                ->with('institution')
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            $resumen = [
                'total_deudas' => $deudas->count(),
                'monto_total' => $deudas->sum('monto'),
                'deudas_vencidas' => $deudas->where('estado', 'Vencida')->count(),
                'monto_vencido' => $deudas->where('estado', 'Vencida')->sum('monto'),
            ];

            Log::info('Acceso a deudas via link', [
                'cliente_id' => $clientToken->id_cliente,
                'cliente_nombre' => $client->nombre,
                'token_id' => $clientToken->id_token
            ]);

            return view('client.deudas-link', [
                'client' => $client,
                'deudas' => $deudas,
                'resumen' => $resumen,
                'token' => $token,
                'tokenId' => $clientToken->id_token
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar deudas con token', [
                'error' => $e->getMessage()
            ]);

            return view('client.deudas-expirado', [
                'mensaje' => 'Error al procesar la solicitud'
            ]);
        }
    }

    /**
     * Marcar token como usado (después de ver las deudas)
     * POST /api/marcar-token-usado/{id_token}
     */
    public function marcarTokenUsado($id_token)
    {
        try {
            $clientToken = ClientToken::find($id_token);

            if (!$clientToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token no encontrado'
                ], 404);
            }

            $clientToken->update([
                'used' => true,
                'used_at' => now()
            ]);

            Log::info('Token marcado como usado', [
                'token_id' => $id_token,
                'cliente_id' => $clientToken->id_cliente
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token marcado como usado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar mensaje por WhatsApp usando API de Whatsurvey
     */
    private function sendWhatsAppViaWhatsurvey($telefono, $mensaje)
    {
        try {
            $apiUrl = env('WHATSURVEY_API_URL', 'https://whatsurvey.mx/api');
            $apiToken = env('WHATSURVEY_API_TOKEN');
            $sessionName = env('WHATSURVEY_API_SESSION_NAME', 'default');

            if (!$apiToken) {
                return [
                    'success' => false,
                    'error' => 'WHATSURVEY_API_TOKEN no configurada'
                ];
            }

            $chatId = $this->formatPhoneForWhatsApp($telefono);

            if (!$chatId) {
                return [
                    'success' => false,
                    'error' => 'Formato de teléfono inválido'
                ];
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$apiUrl}/messages", [
                    'sessionName' => $sessionName,
                    'chatId' => $chatId,
                    'text' => $mensaje,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => $data['ok'] ?? false,
                    'data' => $data
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'Error al enviar'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formatear teléfono al formato WhatsApp
     */
    private function formatPhoneForWhatsApp($telefono)
    {
        try {
            $telefono = preg_replace('/[^0-9]/', '', $telefono);

            if (substr($telefono, 0, 1) === '0') {
                $telefono = substr($telefono, 1);
            }

            if (strlen($telefono) === 10) {
                $telefono = '521' . $telefono;
            } elseif (strlen($telefono) === 11) {
                $telefono = '521' . $telefono;
            } elseif (strlen($telefono) !== 12) {
                return null;
            }

            if (strlen($telefono) === 12 && substr($telefono, 3, 1) !== '1') {
                $telefono = substr($telefono, 0, 3) . '1' . substr($telefono, 3);
            }

            return $telefono . '@c.us';
        } catch (\Exception $e) {
            Log::error('Error al formatear teléfono', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
