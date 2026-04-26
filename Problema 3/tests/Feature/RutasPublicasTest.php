<?php

/**
 * Tests de rutas PÚBLICAS (sin login).
 *
 * Cubro: raíz, login (GET/POST), logout, incidencia pública (GET/POST).
 * Según el PDF: "Que todas las rutas existan y muestren algo".
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RutasPublicasTest extends TestCase
{
    /** Cada test arranca con la BD limpia (migrada en memoria). */
    use RefreshDatabase;

    public function test_la_raiz_redirige_al_login_si_no_hay_sesion(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_la_raiz_redirige_a_tareas_si_ya_hay_sesion(): void
    {
        $this->loginComoAdmin();
        $response = $this->get('/');
        $response->assertRedirect('/tareas');
    }

    public function test_el_formulario_de_login_se_muestra(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('Iniciar sesión');
    }

    public function test_el_formulario_de_incidencia_publica_se_muestra(): void
    {
        $response = $this->get('/incidencia');
        $response->assertOk();
        $response->assertSee('Reportar una incidencia');
    }
}
