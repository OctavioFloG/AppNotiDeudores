<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsurveyService
{
    public function sendMessage(string $telefono, string $mensaje): array
    {
        try {
            $apiUrl      = env('WHATSURVEY_API_URL', 'https://whatsurvey.mx/api');
            $apiToken    = env('WHATSURVEY_API_TOKEN');
            $sessionName = env('WHATSURVEY_API_SESSION_NAME', 'default');

            if (!$apiToken) {
                return ['success' => false, 'error' => 'WHATSURVEY_API_TOKEN no configurada'];
            }

            $chatId = $this->formatPhoneForWhatsApp($telefono);
            if (!$chatId) {
                return ['success' => false, 'error' => 'Formato de teléfono inválido'];
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$apiToken,
                    'Content-Type'  => 'application/json',
                ])
                ->post("{$apiUrl}/messages", [
                    'sessionName' => $sessionName,
                    'chatId'      => $chatId,
                    'text'        => $mensaje,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return ['success' => $data['ok'] ?? false, 'data' => $data];
            }

            return [
                'success' => false,
                'error'   => $response->json()['message'] ?? 'Error al enviar',
            ];
        } catch (\Throwable $e) {
            Log::error('Whatsurvey error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function formatPhoneForWhatsApp(string $telefono): ?string
    {
        try {
            $telefono = preg_replace('/[^0-9]/', '', $telefono);

            if (substr($telefono, 0, 1) === '0') {
                $telefono = substr($telefono, 1);
            }

            if (strlen($telefono) === 10) {
                $telefono = '521'.$telefono;
            } elseif (strlen($telefono) === 11) {
                $telefono = '521'.$telefono;
            } elseif (strlen($telefono) !== 12) {
                return null;
            }

            if (strlen($telefono) === 12 && substr($telefono, 3, 1) !== '1') {
                $telefono = substr($telefono, 0, 3).'1'.substr($telefono, 3);
            }

            return $telefono.'@c.us';
        } catch (\Throwable $e) {
            Log::error('Error al formatear teléfono', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
