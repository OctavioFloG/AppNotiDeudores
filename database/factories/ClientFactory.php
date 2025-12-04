<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_institucion' => Institution::factory(),
            'nombre'         => $this->faker->name,
            'telefono'       => $this->faker->numerify('##########'),
            'correo'         => $this->faker->unique()->safeEmail,
            'direccion'      => $this->faker->address,
        ];
    }
}
