<?php

/**
 * Factory de Cuota.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Cuota;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuotaFactory extends Factory
{
    protected $model = Cuota::class;

    public function definition(): array
    {
        return [
            'cliente_id'    => Cliente::factory(),
            'concepto'      => 'Cuota de prueba',
            'fecha_emision' => now()->toDateString(),
            'importe'       => fake()->randomFloat(2, 50, 300),
            'moneda'        => 'EUR',
            'pagada'        => false,
        ];
    }

    /** Estado "pagada". */
    public function pagada(): static
    {
        return $this->state(fn () => [
            'pagada'     => true,
            'fecha_pago' => now()->toDateString(),
        ]);
    }
}
