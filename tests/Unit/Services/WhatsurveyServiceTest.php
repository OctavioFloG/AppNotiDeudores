<?php

namespace Tests\Unit\Services;

use App\Services\WhatsurveyService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WhatsurveyServiceTest extends TestCase
{
    public function test_devuelve_error_si_no_hay_token()
    {
        putenv('WHATSURVEY_API_TOKEN');

        $service = new WhatsurveyService();

        $result = $service->sendMessage('4613788027', 'Hola');

        $this->assertFalse($result['success']);
        $this->assertSame('Sesión no encontrada o no autorizada', $result['error']);
    }

    public function test_formatea_telefono_y_envia_mensaje_ok()
    {
        putenv('WHATSURVEY_API_TOKEN=fake-token');

        Http::fake([
            'https://whatsurvey.mx/api/messages' => Http::response(['ok' => true], 200),
        ]);

        $service = new WhatsurveyService();

        $result = $service->sendMessage('(461) 378-8027', 'Hola WhatsApp');

        $this->assertTrue($result['success']);

        Http::assertSent(function ($request) {
            $authHeader = $request->header('Authorization')[0] ?? '';
            $this->assertStringStartsWith('Bearer ', $authHeader);
            $this->assertSame('application/json', $request->header('Content-Type')[0]);

            $body = $request->data();

            // 2721493602 -> limpia -> len 10 -> 5212721493602 -> agrega 1 tras 521 => 52112721493602
            $this->assertSame('5214613788027@c.us', $body['chatId']);
            $this->assertSame('Hola WhatsApp', $body['text']);

            return true;
        });
    }

    public function test_retorna_error_si_telefono_invalido()
    {
        putenv('WHATSURVEY_API_TOKEN=fake-token');

        Http::fake();

        $service = new WhatsurveyService();

        $result = $service->sendMessage('123', 'Hola');

        $this->assertFalse($result['success']);
        $this->assertSame('Formato de teléfono inválido', $result['error']);

        Http::assertNothingSent();
    }

    public function test_retorna_error_si_respuesta_no_es_successful()
    {
        putenv('WHATSURVEY_API_TOKEN=fake-token');

        Http::fake([
            'https://whatsurvey.mx/api/messages' => Http::response(['message' => 'Fallo'], 400),
        ]);

        $service = new WhatsurveyService();

        $result = $service->sendMessage('4613788027', 'Hola');

        $this->assertFalse($result['success']);
        $this->assertSame('Fallo', $result['error']);
    }
}
