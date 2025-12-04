<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\CuentaPorCobrar;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function muestra_cliente_y_resumen_de_deudas()
    {
        $institution = Institution::factory()->create();

        $user = User::factory()->create([
            'id_institucion' => $institution->id_institucion,
        ]);

        $client = Client::factory()->create([
            'id_institucion' => $institution->id_institucion,
        ]);

        CuentaPorCobrar::factory()->count(2)->create([
            'id_cliente'     => $client->id_cliente,
            'id_institucion' => $institution->id_institucion,
            'estado'         => 'Pendiente',
            'monto'          => 1000,
            'fecha_emision'  => now(),
            'fecha_vencimiento' => now()->addDays(7),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson("/api/institution/clientes/{$client->id_cliente}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'resumen' => [
                        'total_deudas' => 2,
                        'monto_total'  => 2000,
                    ],
                ],
            ]);
    }
}
