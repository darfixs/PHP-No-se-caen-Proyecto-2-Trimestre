<?php

/**
 * Tests del CRUD de tareas, incluyendo:
 *  - El admin puede crearlas y DEBE asignar operario (PDF).
 *  - El operario solo ve las suyas.
 *  - El operario no puede ver una tarea ajena.
 *  - El operario puede completar su propia tarea.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TareasCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_crear_una_tarea_con_operario(): void
    {
        $this->loginComoAdmin();
        $cliente  = Cliente::factory()->create();
        $operario = Empleado::factory()->create(); // tipo Operario por defecto

        $response = $this->post('/tareas/crear', [
            'cliente_id'        => $cliente->id,
            'personaNombre'     => 'Contacto Test',
            'telefono'          => '600111222',
            'correo'            => 'contacto@test.com',
            'descripcionTarea'  => 'Revisar ascensor del 2º piso',
            'direccionTarea'    => 'Calle Mayor 1',
            'poblacion'         => 'Huelva',
            'codigoPostal'      => '21001',
            'estadoTarea'       => 'P',
            'operario_id'       => $operario->id,
            'fechaRealizacion'  => now()->addDays(5)->toDateString(),
        ]);

        $response->assertRedirect('/tareas');
        $this->assertDatabaseHas('tareas', [
            'cliente_id'     => $cliente->id,
            'personaNombre'  => 'Contacto Test',
            'operario_id'    => $operario->id,
        ]);
    }

    public function test_admin_no_puede_crear_tarea_sin_operario(): void
    {
        // PDF: "No se permitirá crear tareas sin asignar operario a los administradores"
        $this->loginComoAdmin();
        $cliente = Cliente::factory()->create();

        $response = $this->from('/tareas/crear')->post('/tareas/crear', [
            'cliente_id'        => $cliente->id,
            'personaNombre'     => 'Contacto Test',
            'telefono'          => '600111222',
            'correo'            => 'contacto@test.com',
            'descripcionTarea'  => 'Revisión',
            'estadoTarea'       => 'P',
            // operario_id omitido a propósito
            'fechaRealizacion'  => now()->addDays(5)->toDateString(),
        ]);

        $response->assertSessionHasErrors('operario_id');
    }

    public function test_fecha_realizacion_debe_ser_posterior_a_hoy(): void
    {
        $this->loginComoAdmin();
        $cliente  = Cliente::factory()->create();
        $operario = Empleado::factory()->create();

        $response = $this->from('/tareas/crear')->post('/tareas/crear', [
            'cliente_id'        => $cliente->id,
            'personaNombre'     => 'Contacto Test',
            'telefono'          => '600111222',
            'correo'            => 'contacto@test.com',
            'descripcionTarea'  => 'Revisión',
            'estadoTarea'       => 'P',
            'operario_id'       => $operario->id,
            'fechaRealizacion'  => now()->subDays(2)->toDateString(), // ayer
        ]);

        $response->assertSessionHasErrors('fechaRealizacion');
    }

    public function test_operario_solo_ve_sus_propias_tareas(): void
    {
        // PDF: "Un operario solo verá las tareas que tiene asignadas"
        $operarioA = Empleado::factory()->create();
        $operarioB = Empleado::factory()->create();

        // 2 tareas de A, 3 de B
        Tarea::factory()->count(2)->create(['operario_id' => $operarioA->id]);
        Tarea::factory()->count(3)->create(['operario_id' => $operarioB->id]);

        $this->actingAs($operarioA);

        // El listado del operario A debe tener SOLO 2 registros
        $response = $this->get('/tareas');
        $response->assertOk();

        // Compruebo también vía el modelo
        $lista = Tarea::listadoParaOperario($operarioA->id, 10);
        $this->assertCount(2, $lista);
    }

    public function test_operario_no_puede_ver_tarea_ajena(): void
    {
        $operarioA = Empleado::factory()->create();
        $operarioB = Empleado::factory()->create();
        $tareaDeB  = Tarea::factory()->create(['operario_id' => $operarioB->id]);

        $this->actingAs($operarioA);

        $response = $this->get('/tareas/detalle/'.$tareaDeB->id);
        $response->assertForbidden();
    }

    public function test_operario_puede_completar_su_tarea(): void
    {
        $operario = $this->loginComoOperario();
        $tarea = Tarea::factory()->create([
            'operario_id' => $operario->id,
            'estadoTarea' => 'P',
        ]);

        $response = $this->post('/tareas/completar/'.$tarea->id, [
            'estadoTarea'            => 'R',
            'anotacionesPosteriores' => 'Todo revisado, funciona bien.',
        ]);

        $response->assertRedirect('/tareas');
        $this->assertDatabaseHas('tareas', [
            'id'                     => $tarea->id,
            'estadoTarea'            => 'R',
            'anotacionesPosteriores' => 'Todo revisado, funciona bien.',
        ]);
    }
}
