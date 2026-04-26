<?php

/**
 * Tests del CRUD de clientes.
 *
 * Incluye el caso que te dio error ayer:
 *   "no se puede borrar un cliente si tiene tareas asociadas".
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Pais;
use App\Models\Tarea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_crear_un_cliente(): void
    {
        $this->loginComoAdmin();
        $pais = Pais::factory()->create();

        $datos = [
            'cif'              => 'B12345678',
            'nombre'           => 'Empresa Test S.L.',
            'telefono'         => '959000000',
            'correo'           => 'empresa@test.com',
            'cuenta_corriente' => 'ES9121000418450200051332',
            'pais_id'          => $pais->id,
            'moneda'           => 'EUR',
            'importe_cuota'    => 100.50,
        ];

        $response = $this->post('/clientes/crear', $datos);

        $response->assertRedirect('/clientes');
        $this->assertDatabaseHas('clientes', [
            'cif'    => 'B12345678',
            'nombre' => 'Empresa Test S.L.',
        ]);
    }

    public function test_validacion_rechaza_cliente_sin_cif(): void
    {
        $this->loginComoAdmin();
        $pais = Pais::factory()->create();

        $response = $this->from('/clientes/crear')->post('/clientes/crear', [
            // cif en blanco a propósito
            'nombre'        => 'Cliente raro',
            'telefono'      => '959000000',
            'correo'        => 'raro@test.com',
            'pais_id'       => $pais->id,
            'moneda'        => 'EUR',
            'importe_cuota' => 100,
        ]);

        $response->assertSessionHasErrors('cif');
    }

    public function test_cliente_sin_tareas_se_puede_eliminar(): void
    {
        $this->loginComoAdmin();
        $cliente = Cliente::factory()->create();

        $response = $this->post('/clientes/eliminar/'.$cliente->id);

        $response->assertRedirect('/clientes');
        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    public function test_cliente_con_tareas_NO_se_puede_eliminar(): void
    {
        $this->loginComoAdmin();

        $cliente = Cliente::factory()->create();
        // Le asigno una tarea
        Tarea::factory()->create(['cliente_id' => $cliente->id]);

        $response = $this->post('/clientes/eliminar/'.$cliente->id);

        // Se queda en la BD y me devuelve al listado con mensaje de error
        $response->assertRedirect('/clientes');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('clientes', ['id' => $cliente->id]);
    }
}
