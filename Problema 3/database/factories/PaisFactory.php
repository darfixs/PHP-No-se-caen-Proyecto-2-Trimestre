<?php

/**
 * Factory de Pais para los tests.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaisFactory extends Factory
{
    protected $model = Pais::class;

    public function definition(): array
    {
        return [
            'codigo' => strtoupper(fake()->unique()->lexify('??')),
            'nombre' => fake()->country(),
            'moneda' => 'EUR',
        ];
    }
}
