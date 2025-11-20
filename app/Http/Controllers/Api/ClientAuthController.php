<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientAuthController extends Controller
{
    /**
     * Generar token y enviar al cliente vía WhatsApp
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
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            // Buscar token pendiente y no expirado
            $existingToken = ClientToken::where('id_cliente', $id_cliente)
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingToken) {
                // Enviar el mismo token pendiente y no generar uno nuevo
                $token = $existingToken->token;
                $expiresAt = $existingToken->expires_at;
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
            }

            $mensaje = "Tu código de acceso es: {$token}. Válido por 5 minutos.";

            // Enviar por Whatsurvey (adapta esta función después)
            // $respuesta_ws = $this->sendWhatsAppToken($client->telefono, $mensaje);

            return response()->json([
                'success' => true,
                'message' => $existingToken->wasRecentlyCreated
                    ? 'Token generado y enviado exitosamente al cliente'
                    : 'Ya existe un token válido pendiente, se reenvió el mismo',
                'data' => [
                    'id_token' => $existingToken->id_token,
                    'id_cliente' => $existingToken->id_cliente,
                    'cliente_nombre' => $client->nombre,
                    'cliente_telefono' => $client->telefono,
                    'token' => $token,
                    'expires_at' => $expiresAt,
                    'message' => $mensaje,
                    // 'respuesta_whatsurvey' => $respuesta_ws,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar o enviar token',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar token y autenticar cliente
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

            // Generar token de sesión con Sanctum
            $authToken = $client->createToken('client_auth')->plainTextToken;

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
            // Obtener cliente desde el token
            $client = $request->user();

            // Obtener deudas del cliente
            $debts = $client->cuentasPorCobrar()
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            $summary = [
                'total_deudas' => $debts->count(),
                'total_pendiente' => $debts->where('estado', 'Pendiente')->sum('monto'),
                'total_vencidas' => $debts->where('estado', 'Vencida')->sum('monto'),
                'total_pagadas' => $debts->where('estado', 'Pagada')->sum('monto'),
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
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener deudas',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
