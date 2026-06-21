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
            'nom' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefon' => fake()->phoneNumber(),
            'adreca' => fake()->optional()->address(),
            'numero_document_identitat' => fake()->optional()->bothify('########?'),
        ];
    }
}
