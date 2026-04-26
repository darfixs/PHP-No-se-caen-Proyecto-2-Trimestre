<?php

/**
 * Clase base de la que heredan todos los tests.
 *
 * Añado unos cuantos helpers para no repetir código en los tests.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Tests;

use App\Models\Empleado;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Crea un admin y me logueo como él. Devuelve el modelo por si
     * el test lo necesita.
     */
    protected function loginComoAdmin(): Empleado
    {
        $admin = Empleado::factory()->administrador()->create();
        $this->actingAs($admin);
        return $admin;
    }

    /**
     * Crea un operario y me logueo como él.
     */
    protected function loginComoOperario(): Empleado
    {
        $operario = Empleado::factory()->create(); // por defecto ya es Operario
        $this->actingAs($operario);
        return $operario;
    }
}
