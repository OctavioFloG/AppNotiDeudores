<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lista_solo_clientes_de_la_institucion_autenticada()
    {
        $institution1 = Institution::factory()->create();
        $institution2 = Institution::factory()->create();

        $user = User::factory()->create([
            'id_institucion' => $institution1->id_institucion,
        ]);

        Client::factory()->count(2)->create([
            'id_institucion' => $institution1->id_institucion,
        ]);

        Client::factory()->count(3)->create([
            'id_institucion' => $institution2->id_institucion,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/institution/clientes');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data');
    }
}
