<?php

namespace Database\Factories;

use App\Models\Estudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estudiant>
 */
class EstudiantFactory extends Factory
{

    protected $model = Estudiant::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->optional()->address(),
            'numero_documento_identidad' => fake()->optional()->bothify('########?'),
        ];
    }
}
