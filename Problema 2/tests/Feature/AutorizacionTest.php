<?php

/**
 * Tests de autorización.
 *
 * Verifica que el middleware SoloAdmin hace su trabajo:
 * un operario intentando entrar a rutas exclusivas del admin recibe 403.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorizacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_operario_no_puede_ver_empleados(): void
    {
        $this->loginComoOperario();
        $this->get('/empleados')->assertForbidden(); // 403
    }

    public function test_operario_no_puede_ver_clientes(): void
    {
        $this->loginComoOperario();
        $this->get('/clientes')->assertForbidden();
    }

    public function test_operario_no_puede_ver_cuotas(): void
    {
        $this->loginComoOperario();
        $this->get('/cuotas')->assertForbidden();
    }

    public function test_operario_no_puede_crear_empleado(): void
    {
        $this->loginComoOperario();
        $this->get('/empleados/crear')->assertForbidden();
    }

    public function test_operario_no_puede_crear_tarea(): void
    {
        $this->loginComoOperario();
        $this->get('/tareas/crear')->assertForbidden();
    }
}
