<?php

/**
 * Tests del CRUD de empleados.
 *
 * Cubre:
 *  - Envío del formulario de alta → persistencia en BD.
 *  - Validación: datos incompletos → errores.
 *  - Edición correcta.
 *  - Borrado.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpleadosCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_crear_un_empleado(): void
    {
        $this->loginComoAdmin();

        $datos = [
            'dni'        => '99999999Z',
            'nombre'     => 'Nuevo Operario',
            'correo'     => 'nuevo@nosecaen.com',
            'telefono'   => '600111222',
            'direccion'  => 'Calle de prueba 1',
            'fecha_alta' => now()->toDateString(),
            'tipo'       => 'Operario',
            'password'   => 'clavetest',
        ];

        $response = $this->post('/empleados/crear', $datos);

        $response->assertRedirect('/empleados');
        $this->assertDatabaseHas('empleados', [
            'dni'    => '99999999Z',
            'correo' => 'nuevo@nosecaen.com',
            'tipo'   => 'Operario',
        ]);
    }

    public function test_validacion_rechaza_empleado_sin_nombre(): void
    {
        $this->loginComoAdmin();

        $response = $this->from('/empleados/crear')->post('/empleados/crear', [
            'dni'        => '11111111A',
            // nombre a propósito vacío
            'correo'     => 'test@test.com',
            'fecha_alta' => now()->toDateString(),
            'tipo'       => 'Operario',
            'password'   => 'clavetest',
        ]);

        $response->assertRedirect('/empleados/crear');
        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseMissing('empleados', ['correo' => 'test@test.com']);
    }

    public function test_validacion_rechaza_correo_duplicado(): void
    {
        $this->loginComoAdmin();

        // Ya existe un empleado con este correo
        Empleado::factory()->create(['correo' => 'duplicado@nosecaen.com']);

        $response = $this->from('/empleados/crear')->post('/empleados/crear', [
            'dni'        => '77777777X',
            'nombre'     => 'Otro',
            'correo'     => 'duplicado@nosecaen.com', // colisión
            'fecha_alta' => now()->toDateString(),
            'tipo'       => 'Operario',
            'password'   => 'clavetest',
        ]);

        $response->assertSessionHasErrors('correo');
    }

    public function test_admin_puede_editar_un_empleado(): void
    {
        $this->loginComoAdmin();
        $empleado = Empleado::factory()->create([
            'nombre' => 'Antiguo nombre',
        ]);

        $response = $this->post('/empleados/editar/'.$empleado->id, [
            'dni'        => $empleado->dni,
            'nombre'     => 'Nombre actualizado',
            'correo'     => $empleado->correo,
            'fecha_alta' => $empleado->fecha_alta->toDateString(),
            'tipo'       => $empleado->tipo,
            // password vacío → se deja la antigua
        ]);

        $response->assertRedirect('/empleados');
        $this->assertDatabaseHas('empleados', [
            'id'     => $empleado->id,
            'nombre' => 'Nombre actualizado',
        ]);
    }

    public function test_admin_puede_eliminar_un_empleado(): void
    {
        $this->loginComoAdmin();
        $empleado = Empleado::factory()->create();

        $response = $this->post('/empleados/eliminar/'.$empleado->id);

        $response->assertRedirect('/empleados');
        $this->assertDatabaseMissing('empleados', ['id' => $empleado->id]);
    }
}
