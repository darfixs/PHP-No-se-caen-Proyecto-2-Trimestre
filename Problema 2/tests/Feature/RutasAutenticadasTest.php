<?php

/**
 * Tests de rutas AUTENTICADAS.
 *
 * Comprueba que:
 *  - Sin sesión te redirige al login.
 *  - Con sesión, cada ruta responde 200 y muestra contenido.
 *  - La navegación básica entre listados funciona.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RutasAutenticadasTest extends TestCase
{
    use RefreshDatabase;

    /*  SIN SESIÓN  */

    public function test_sin_sesion_tareas_redirige_al_login(): void
    {
        $response = $this->get('/tareas');
        $response->assertRedirect('/login');
    }

    public function test_sin_sesion_empleados_redirige_al_login(): void
    {
        $response = $this->get('/empleados');
        $response->assertRedirect('/login');
    }

    public function test_sin_sesion_clientes_redirige_al_login(): void
    {
        $response = $this->get('/clientes');
        $response->assertRedirect('/login');
    }

    public function test_sin_sesion_cuotas_redirige_al_login(): void
    {
        $response = $this->get('/cuotas');
        $response->assertRedirect('/login');
    }

    /*  CON SESIÓN (ADMIN)  */

    public function test_admin_ve_listado_de_tareas(): void
    {
        $this->loginComoAdmin();
        $this->get('/tareas')->assertOk()->assertSee('tareas', false);
    }

    public function test_admin_ve_listado_de_empleados(): void
    {
        $this->loginComoAdmin();
        $this->get('/empleados')->assertOk()->assertSee('Listado de empleados');
    }

    public function test_admin_ve_listado_de_clientes(): void
    {
        $this->loginComoAdmin();
        $this->get('/clientes')->assertOk()->assertSee('Listado de clientes');
    }

    public function test_admin_ve_listado_de_cuotas(): void
    {
        $this->loginComoAdmin();
        $this->get('/cuotas')->assertOk()->assertSee('Listado de cuotas');
    }

    public function test_admin_ve_formulario_alta_cliente(): void
    {
        $this->loginComoAdmin();
        $this->get('/clientes/crear')->assertOk()->assertSee('Nuevo cliente');
    }

    public function test_admin_ve_formulario_alta_empleado(): void
    {
        $this->loginComoAdmin();
        $this->get('/empleados/crear')->assertOk()->assertSee('Nuevo empleado');
    }

    public function test_admin_ve_su_perfil(): void
    {
        $this->loginComoAdmin();
        $this->get('/perfil')->assertOk()->assertSee('Mi perfil');
    }

    /*  CON SESIÓN (OPERARIO)  */

    public function test_operario_ve_listado_de_tareas(): void
    {
        $this->loginComoOperario();
        $this->get('/tareas')->assertOk();
    }

    public function test_operario_ve_su_perfil(): void
    {
        $this->loginComoOperario();
        $this->get('/perfil')->assertOk()->assertSee('Mi perfil');
    }
}
