<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientToken;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClientAuthController extends Controller
{
    /**
     * Generar token y enviar al cliente vía WhatsApp (Whatsurvey)
     */
    public function generateAndSendToken(Request $request, $id_cliente)
    {
        $validator = Validator::make(['id_cliente' => $id_cliente], [
            'id_cliente' => 'required|integer|exists:clients,id_cliente',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado',
                'errors' => $validator->errors()
            ], 404);
        }

        try {
            $client = Client::where('id_cliente', $id_cliente)
                ->where('id_institucion', auth('sanctum')->user()->id_institucion)
                ->firstOrFail();

            // Buscar token pendiente y no expirado
            $existingToken = ClientToken::where('id_cliente', $id_cliente)
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingToken) {
                // Enviar el mismo token pendiente
                $token = $existingToken->token;
                $expiresAt = $existingToken->expires_at;
                $wasRecentlyCreated = false;
            } else {
                // Generar nuevo token
                $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = now()->addMinutes(5);

                $existingToken = ClientToken::create([
                    'id_cliente' => $id_cliente,
                    'token' => $token,
                    'expires_at' => $expiresAt,
                    'used' => false,
                ]);
                $wasRecentlyCreated = true;
            }

            // Mensaje a enviar
            $mensaje = "Tu código de acceso es: {$token}\nVálido por 5 minutos.";

            // Enviar por WhatsApp y guardar notificación
            $respuestaWhatsurvey = $this->enviarNotificacionWhatsApp(
                $client,
                $mensaje,
                'Código de acceso',
                'token_acceso'
            );

            if (!$respuestaWhatsurvey['success']) {
                Log::warning('Error al enviar WhatsApp', [
                    'cliente' => $client->nombre,
                    'telefono' => $client->telefono,
                    'error' => $respuestaWhatsurvey['error']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar token por WhatsApp',
                    'error' => $respuestaWhatsurvey['error']
                ], 500);
            }

            Log::info('Token enviado exitosamente', [
                'cliente_id' => $client->id_cliente,
                'telefono' => $client->telefono,
                'notificacion_id' => $respuestaWhatsurvey['notificacion_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => $wasRecentlyCreated
                    ? 'Token generado y enviado exitosamente al cliente'
                    : 'Ya existe un token válido pendiente, se reenvió el mismo',
                'data' => [
                    'id_token' => $existingToken->id,
                    'id_cliente' => $existingToken->id_cliente,
                    'cliente_nombre' => $client->nombre,
                    'cliente_telefono' => $client->telefono,
                    'token' => $token,
                    'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
                    'message' => $mensaje,
                    'notificacion_id' => $respuestaWhatsurvey['notificacion_id'],
                    'whatsurvey_status' => $respuestaWhatsurvey['data']['message'] ?? 'Enviado'
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al generar token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar o enviar token',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar token e iniciar sesión del cliente
     */
    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_cliente' => 'required|integer|exists:clients,id_cliente',
            'token'      => 'required|string|size:6',
        ], [
            'token.size' => 'El token debe tener 6 dígitos.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $client = Client::findOrFail($request->id_cliente);

            // Buscar token válido y no expirado
            $clientToken = ClientToken::where('id_cliente', $request->id_cliente)
                ->where('token', $request->token)
                ->where('used', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$clientToken) {
                Log::warning('Intento de login con token inválido', [
                    'cliente_id' => $request->id_cliente,
                    'token_ingresado' => substr($request->token, 0, 3) . '***'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido, expirado o ya utilizado',
                ], 401);
            }

            // Marcar token como usado
            $clientToken->update([
                'used' => true,
                'used_at' => Carbon::now()
            ]);

            // Generar token de sesión con Sanctum para cliente
            $authToken = $client->createToken('client_auth')->plainTextToken;

            Log::info('Cliente autenticado exitosamente', [
                'cliente_id' => $client->id_cliente,
                'nombre' => $client->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Autenticación exitosa',
                'data' => [
                    'client' => [
                        'id' => $client->id_cliente,
                        'nombre' => $client->nombre,
                        'telefono' => $client->telefono,
                        'correo' => $client->correo,
                    ],
                    'token' => $authToken,
                    'token_type' => 'Bearer',
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error en validación de token', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en autenticación',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener deudas del cliente autenticado
     */
    public function myDebts(Request $request)
    {
        try {
            $client = $request->user('sanctum');

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            // Obtener deudas del cliente
            $debts = Client::find($client->id_cliente)
                ->cuentasPorCobrar()
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            $totalDebts = $debts->count();
            $totalPendiente = $debts->where('estado', 'Pendiente')->sum('monto');
            $totalVencidas = $debts->where('estado', 'Vencida')->sum('monto');
            $totalPagadas = $debts->where('estado', 'Pagada')->sum('monto');

            $summary = [
                'total_deudas' => $totalDebts,
                'total_pendiente' => $totalPendiente,
                'total_vencidas' => $totalVencidas,
                'total_pagadas' => $totalPagadas,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'client' => $client,
                    'summary' => $summary,
                    'debts' => $debts,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener deudas', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener deudas',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar notificación por WhatsApp y guardarla en BD
     */
    private function enviarNotificacionWhatsApp($client, $mensaje, $asunto = null, $tipo_notificacion = 'automatica')
    {
        try {
            // 1. Enviar por WhatsApp via Whatsurvey
            $respuestaWhatsurvey = $this->sendWhatsAppViaWhatsurvey($client->telefono, $mensaje);

            if (!$respuestaWhatsurvey['success']) {
                return [
                    'success' => false,
                    'error' => $respuestaWhatsurvey['error']
                ];
            }

            // 2. Guardar en base de datos
            $notificacion = Notificacion::create([
                'id_institucion' => $client->id_institucion,
                'id_cliente' => $client->id_cliente,
                'tipo' => 'whatsapp',
                'mensaje' => $mensaje,
                'asunto' => $asunto ?? 'Notificación',
                'destinatario' => $client->telefono,
                'estado' => 'enviada',
                'fecha_envio' => now()
            ]);

            Log::info('Notificación guardada', [
                'notificacion_id' => $notificacion->id_notificacion,
                'cliente_id' => $client->id_cliente,
                'tipo' => $tipo_notificacion
            ]);

            return [
                'success' => true,
                'notificacion_id' => $notificacion->id_notificacion,
                'data' => $respuestaWhatsurvey['data']
            ];

        } catch (\Exception $e) {
            Log::error('Error al enviar notificación WhatsApp', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Enviar mensaje por WhatsApp usando API de Whatsurvey
     * 
     * Configuración necesaria en .env:
     * WHATSURVEY_API_URL=https://api.whatsurvey.mx
     * WHATSURVEY_API_TOKEN=tu_api_token
     * WHATSURVEY_API_SESSION_NAME=nombre-de-la-sesion
     */
    private function sendWhatsAppViaWhatsurvey($telefono, $mensaje)
    {
        try {
            $apiUrl = env('WHATSURVEY_API_URL', 'https://whatsurvey.mx/api');
            $apiToken = env('WHATSURVEY_API_TOKEN');
            $sessionName = env('WHATSURVEY_API_SESSION_NAME', 'default');

            Log::info('API URL de Whatsurvey utilizada', [
                'apiUrl' => $apiUrl
            ]);

            if (!$apiToken) {
                return [
                    'success' => false,
                    'error' => 'WHATSURVEY_API_TOKEN no configurada'
                ];
            }

            // Formatear teléfono para WhatsApp (México)
            $chatId = $this->formatPhoneForWhatsApp($telefono);

            if (!$chatId) {
                return [
                    'success' => false,
                    'error' => 'Formato de teléfono inválido'
                ];
            }

            Log::info('Enviando mensaje por Whatsurvey', [
                'telefono_original' => $telefono,
                'chatId' => $chatId,
                'session' => $sessionName
            ]);

            // Realizar petición a Whatsurvey
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

            Log::info('Respuesta de Whatsurvey', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => $data['ok'] ?? false,
                    'data' => $data
                ];
            } else {
                $errorData = $response->json();
                return [
                    'success' => false,
                    'error' => $errorData['message'] ?? 'Error al enviar mensaje: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception en Whatsurvey', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formatear teléfono al formato WhatsApp para MÉXICO
     * Ejemplos:
     * 5512345678 -> 525512345678@c.us
     * 12345678 -> 5212345678@c.us
     * 05512345678 -> 525512345678@c.us
     */
    private function formatPhoneForWhatsApp($telefono)
    {
        try {
            // Remover caracteres especiales
            $telefono = preg_replace('/[^0-9]/', '', $telefono);

            // Si comienza con 0, quitar el 0
            if (substr($telefono, 0, 1) === '0') {
                $telefono = substr($telefono, 1);
            }

            // Si tiene 10 dígitos (número local), agregar código país 52
            if (strlen($telefono) === 10) {
                $telefono = '521' . $telefono;
            }
            // Si tiene 11 dígitos, probablemente ya incluye el 52 parcial
            elseif (strlen($telefono) === 11) {
                $telefono = '521' . $telefono;
            }
            // Si ya tiene 12 dígitos (52 + 10 dígitos), validar
            elseif (strlen($telefono) !== 12) {
                Log::warning('Teléfono con longitud no válida para México', [
                    'telefono_procesado' => $telefono,
                    'longitud' => strlen($telefono)
                ]);
                return null;
            }

            // Agregar un 1 después del código de país si no está presente
            if (strlen($telefono) === 12 && substr($telefono, 3, 1) !== '1') {
                $telefono = substr($telefono, 0, 3) . '1' . substr($telefono, 3);
            }

            // Formato final para WhatsApp
            return $telefono . '@c.us';
        } catch (\Exception $e) {
            Log::error('Error al formatear teléfono', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
