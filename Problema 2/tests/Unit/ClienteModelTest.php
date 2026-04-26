<?php

/**
 * Tests UNIT del modelo Cliente.
 *
 * Prueba la lógica pura del modelo sin pasar por HTTP.
 * Esto es lo que el PDF quiere ver: la lógica vive en los modelos,
 * así que hay que poder testearla directamente.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Unit;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_autenticar_por_cif_y_telefono_con_datos_correctos(): void
    {
        $cliente = Cliente::factory()->create([
            'cif'      => 'B12341234',
            'telefono' => '959999888',
        ]);

        $encontrado = Cliente::autenticarPorCifTelefono('B12341234', '959999888');

        $this->assertNotNull($encontrado);
        $this->assertEquals($cliente->id, $encontrado->id);
    }

    public function test_autenticar_con_telefono_erroneo_devuelve_null(): void
    {
        Cliente::factory()->create([
            'cif'      => 'B12341234',
            'telefono' => '959999888',
        ]);

        $this->assertNull(
            Cliente::autenticarPorCifTelefono('B12341234', 'telefono-malo')
        );
    }

    public function test_autenticar_con_cif_inexistente_devuelve_null(): void
    {
        $this->assertNull(
            Cliente::autenticarPorCifTelefono('NO_EXISTE', '959999888')
        );
    }
}
