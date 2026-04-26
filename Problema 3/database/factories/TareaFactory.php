<?php

/**
 * Factory de Tarea.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaFactory extends Factory
{
    protected $model = Tarea::class;

    public function definition(): array
    {
        return [
            'cliente_id'            => Cliente::factory(),
            'personaNombre'         => fake()->name(),
            'telefono'              => fake()->numerify('6########'),
            'correo'                => fake()->safeEmail(),
            'descripcionTarea'      => fake()->sentence(10),
            'direccionTarea'        => fake()->streetAddress(),
            'poblacion'             => fake()->city(),
            'codigoPostal'          => fake()->numerify('#####'),
            'provincia'             => null,
            'estadoTarea'           => 'P',
            'operario_id'           => Empleado::factory(),
            'fechaRealizacion'      => now()->addDays(7)->toDateString(),
            'anotacionesAnteriores' => fake()->sentence(5),
        ];
    }

    /** Estado "sin operario asignado" (caso de incidencia pública). */
    public function sinOperario(): static
    {
        return $this->state(fn () => ['operario_id' => null]);
    }

    /** Estado "realizada". */
    public function realizada(): static
    {
        return $this->state(fn () => ['estadoTarea' => 'R']);
    }
}
