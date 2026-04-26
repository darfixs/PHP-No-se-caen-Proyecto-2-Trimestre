<?php

/**
 * Tests UNIT del modelo Empleado.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Unit;

use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpleadoModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_se_identifica_correctamente(): void
    {
        $admin = Empleado::factory()->administrador()->create();
        $this->assertTrue($admin->esAdmin());
        $this->assertFalse($admin->esOperario());
    }

    public function test_operario_se_identifica_correctamente(): void
    {
        $ope = Empleado::factory()->create(); // Operario por defecto
        $this->assertTrue($ope->esOperario());
        $this->assertFalse($ope->esAdmin());
    }

    public function test_metodo_operarios_devuelve_solo_operarios(): void
    {
        Empleado::factory()->count(2)->administrador()->create();
        Empleado::factory()->count(3)->create(); // operarios

        $operarios = Empleado::operarios();

        $this->assertCount(3, $operarios);
        foreach ($operarios as $op) {
            $this->assertEquals('Operario', $op->tipo);
        }
    }
}
