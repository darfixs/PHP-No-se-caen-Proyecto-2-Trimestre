<?php

/**
 * Factory de Provincia.
 *
 * Ojo: la PK es "codigo" (string de 2 chars), no id autoincremental.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinciaFactory extends Factory
{
    protected $model = Provincia::class;

    public function definition(): array
    {
        return [
            'codigo' => fake()->unique()->numerify('##'),
            'nombre' => fake()->state(),
        ];
    }
}
