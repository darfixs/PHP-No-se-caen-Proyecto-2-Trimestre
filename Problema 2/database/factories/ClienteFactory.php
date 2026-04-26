<?php

/**
 * Factory de Cliente.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'cif'              => strtoupper(fake()->unique()->bothify('?########')),
            'nombre'           => fake()->company(),
            'telefono'         => fake()->numerify('6########'),
            'correo'           => fake()->unique()->safeEmail(),
            'cuenta_corriente' => 'ES' . fake()->numerify('######################'),
            // Crea un país automáticamente si no se le pasa uno
            'pais_id'          => Pais::factory(),
            'moneda'           => 'EUR',
            'importe_cuota'    => fake()->randomFloat(2, 50, 500),
        ];
    }
}
