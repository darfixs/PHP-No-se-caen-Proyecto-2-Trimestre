<?php

/**
 * Factory de Empleado.
 *
 * Permite crear empleados falsos en los tests:
 *   Empleado::factory()->create();
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition(): array
    {
        return [
            'dni'        => strtoupper(fake()->bothify('########?')),
            'nombre'     => fake()->name(),
            'correo'     => fake()->unique()->safeEmail(),
            'telefono'   => fake()->phoneNumber(),
            'direccion'  => fake()->address(),
            'fecha_alta' => now(),
            'tipo'       => 'Operario',
            'password'   => bcrypt('test12345'),
        ];
    }

    /** Estado "administrador". */
    public function administrador(): static
    {
        return $this->state(fn () => ['tipo' => 'Administrador']);
    }
}
