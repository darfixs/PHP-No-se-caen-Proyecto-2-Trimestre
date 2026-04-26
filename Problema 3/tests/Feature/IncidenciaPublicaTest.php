<?php

/**
 * Tests del formulario público de incidencias de cliente.
 *
 * Requisitos PDF:
 *  - El cliente introduce CIF + teléfono para identificarse.
 *  - Si no coinciden con ningún cliente → se rechaza.
 *  - Si coinciden → se crea la tarea SIN operario asignado.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidenciaPublicaTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_con_cif_y_telefono_correctos_registra_incidencia(): void
    {
        $cliente = Cliente::factory()->create([
            'cif'      => 'B99999999',
            'telefono' => '959000111',
        ]);

        $response = $this->post('/incidencia', [
            'cif'              => 'B99999999',
            'telefonoCliente'  => '959000111',
            'personaNombre'    => 'Juan Cliente',
            'telefono'         => '600123456',
            'correo'           => 'juan.cliente@example.com',
            'descripcionTarea' => 'Ascensor parado en planta 3',
            'fechaRealizacion' => now()->addDays(3)->toDateString(),
        ]);

        // Se muestra la vista "gracias"
        $response->assertOk();
        $response->assertSee('Incidencia registrada');

        // Se creó la tarea asociada al cliente, SIN operario
        $this->assertDatabaseHas('tareas', [
            'cliente_id'  => $cliente->id,
            'operario_id' => null,
            'estadoTarea' => 'P',
        ]);
    }

    public function test_cliente_con_cif_valido_pero_telefono_erroneo_es_rechazado(): void
    {
        Cliente::factory()->create([
            'cif'      => 'B88888888',
            'telefono' => '959111222',
        ]);

        $response = $this->from('/incidencia')->post('/incidencia', [
            'cif'              => 'B88888888',
            'telefonoCliente'  => 'TELEFONO_MALO', // no coincide
            'personaNombre'    => 'Pepe',
            'telefono'         => '600000000',
            'correo'           => 'pepe@pepe.com',
            'descripcionTarea' => 'Test',
            'fechaRealizacion' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect('/incidencia');
        $response->assertSessionHasErrors('cif');

        // Y no se creó la tarea
        $this->assertDatabaseCount('tareas', 0);
    }

    public function test_cif_que_no_existe_es_rechazado(): void
    {
        Cliente::factory()->create([
            'cif'      => 'B77777777',
            'telefono' => '959333444',
        ]);

        $response = $this->from('/incidencia')->post('/incidencia', [
            'cif'              => 'INEXISTENTE',
            'telefonoCliente'  => '959333444',
            'personaNombre'    => 'Pepe',
            'telefono'         => '600000000',
            'correo'           => 'pepe@pepe.com',
            'descripcionTarea' => 'Test',
            'fechaRealizacion' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertSessionHasErrors('cif');
        $this->assertDatabaseCount('tareas', 0);
    }
}
