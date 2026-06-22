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
            'telefon' => $this->generarTelefon(),
            'adreca' => fake()->optional()->address(),
            'numero_document_identitat' => fake()->optional(0.8)->passthrough($this->generarDni()),
        ];
    }

    /**
     * Genera un telèfon espanyol de 9 dígits vàlid.
     */
    private function generarTelefon(): string
    {
        $prefixos = [6, 7, 8, 9];
        $prefix = fake()->randomElement($prefixos);

        return $prefix.fake()->numerify('########');
    }

    /**
     * Genera un DNI espanyol amb la lletra correcta.
     */
    private function generarDni(): string
    {
        $numero = fake()->numberBetween(10000000, 99999999);
        $lletres = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $lletra = $lletres[$numero % 23];

        return $numero.$lletra;
    }
}
