<?php

/**
 * Tests del proceso de login / logout.
 *
 * Aquí cumplimos el segundo requisito del PDF:
 *   "Para algunos formularios deberemos probar su envío y procesado"
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_con_credenciales_correctas_redirige_a_tareas(): void
    {
        // Creo un admin con una clave conocida
        $admin = Empleado::factory()->administrador()->create([
            'correo'   => 'jefe@nosecaen.com',
            'password' => 'secreto123', // el cast 'hashed' ya la hashea
        ]);

        $response = $this->post('/login', [
            'correo'   => 'jefe@nosecaen.com',
            'password' => 'secreto123',
        ]);

        $response->assertRedirect('/tareas');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_con_correo_inexistente_muestra_error(): void
    {
        $response = $this->from('/login')->post('/login', [
            'correo'   => 'nadie@ningunsitio.com',
            'password' => 'loquesea',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['correo']);
        $this->assertGuest(); // nadie se ha logueado
    }

    public function test_login_con_password_incorrecta_muestra_error(): void
    {
        Empleado::factory()->create([
            'correo'   => 'juan@nosecaen.com',
            'password' => 'correcta',
        ]);

        $response = $this->from('/login')->post('/login', [
            'correo'   => 'juan@nosecaen.com',
            'password' => 'INCORRECTA',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['correo']);
        $this->assertGuest();
    }

    public function test_login_sin_datos_muestra_errores_de_validacion(): void
    {
        $response = $this->from('/login')->post('/login', []);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['correo', 'password']);
    }

    public function test_logout_cierra_sesion(): void
    {
        $admin = $this->loginComoAdmin();
        $this->assertAuthenticatedAs($admin);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
