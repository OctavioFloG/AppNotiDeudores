<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    /** @test */
public function valida_telefono_duplicado_en_la_misma_institucion()
{
    $institution = Institution::factory()->create();

    $user = User::factory()->create([
        'id_institucion' => $institution->id_institucion,
    ]);

    Client::factory()->create([
        'id_institucion' => $institution->id_institucion,
        'telefono'       => '2721493602',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/institution/clientes', [
        'nombre'   => 'Otro',
        'telefono' => '2721493602',
        'correo'   => 'otro@example.com',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Error de validación',
        ])
        ->assertJsonStructure(['errors' => ['telefono']]);
}

/** @test */
public function permite_telefono_duplicado_en_diferente_institucion()
{
    $institution1 = Institution::factory()->create();
    $institution2 = Institution::factory()->create();

    $user = User::factory()->create([
        'id_institucion' => $institution1->id_institucion,
    ]);

    Client::factory()->create([
        'id_institucion' => $institution2->id_institucion, // otra institución
        'telefono'       => '2721493602',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/institution/clientes', [
        'nombre'   => 'Nuevo Cliente',
        'telefono' => '2721493602',
        'correo'   => 'nuevo@example.com',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'data' => [
                'nombre'        => 'Nuevo Cliente',
                'telefono'      => '2721493602',
                'correo'        => 'nuevo@example.com',
                'id_institucion'=> $institution1->id_institucion,
            ],
        ]);

    $this->assertDatabaseHas('clients', [
        'nombre'        => 'Nuevo Cliente',
        'telefono'      => '2721493602',
        'correo'        => 'nuevo@example.com',
        'id_institucion'=> $institution1->id_institucion,
    ]);
}

}