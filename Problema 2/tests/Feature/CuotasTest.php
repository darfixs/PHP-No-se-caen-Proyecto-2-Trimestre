<?php

/**
 * Tests de cuotas.
 *
 * Cubre requisitos gordos del PDF:
 *  - "Añadir remesa mensual a todos los clientes" (método del modelo).
 *  - "Enviar de forma automática por correo factura PDF al cliente".
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Tests\Feature;

use App\Mail\CuotaCreadaMail;
use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Pais;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CuotasTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_crear_una_cuota_excepcional(): void
    {
        // Intercepto los correos para que no intenten enviarse de verdad
        Mail::fake();

        $this->loginComoAdmin();
        $cliente = Cliente::factory()->create();

        $response = $this->post('/cuotas/crear', [
            'cliente_id'    => $cliente->id,
            'concepto'      => 'Reparación urgente',
            'fecha_emision' => now()->toDateString(),
            'importe'       => 250.00,
            'moneda'        => 'EUR',
        ]);

        $response->assertRedirect('/cuotas');

        $this->assertDatabaseHas('cuotas', [
            'cliente_id' => $cliente->id,
            'concepto'   => 'Reparación urgente',
            'importe'    => 250.00,
        ]);

        // Y se mandó un correo al cliente
        Mail::assertSent(CuotaCreadaMail::class, function ($mail) use ($cliente) {
            return $mail->hasTo($cliente->correo);
        });
    }

    public function test_generar_remesa_mensual_crea_una_cuota_por_cliente(): void
    {
        Mail::fake();
        $this->loginComoAdmin();

        // 3 clientes con importes distintos
        $pais = Pais::factory()->create();
        Cliente::factory()->count(3)->create([
            'pais_id'       => $pais->id,
            'importe_cuota' => 100.00,
        ]);

        // Lanzo la remesa mensual
        $response = $this->post('/cuotas/remesa');

        $response->assertRedirect('/cuotas');
        $this->assertDatabaseCount('cuotas', 3);

        // Se enviaron 3 correos (uno por cliente)
        Mail::assertSent(CuotaCreadaMail::class, 3);
    }

    public function test_metodo_del_modelo_generarRemesaMensual(): void
    {
        // Este test no pasa por HTTP, prueba directamente el método del modelo.
        // Es lo que pedía el PDF: "lógica en el modelo, no en el controlador",
        // y la manera limpia de demostrarlo es testearla sin controlador.
        $pais = Pais::factory()->create();
        Cliente::factory()->count(4)->create([
            'pais_id'       => $pais->id,
            'importe_cuota' => 150.00,
        ]);

        $totalCreadas = Cuota::generarRemesaMensual('Cuota abril');

        $this->assertEquals(4, $totalCreadas);
        $this->assertDatabaseCount('cuotas', 4);
        $this->assertDatabaseHas('cuotas', [
            'concepto' => 'Cuota abril',
            'importe'  => 150.00,
            'pagada'   => false,
        ]);
    }

    public function test_factura_pdf_se_puede_mostrar_en_el_navegador(): void
    {
        $this->loginComoAdmin();
        $cuota = Cuota::factory()->create();

        $response = $this->get('/facturas/mostrar/'.$cuota->id);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_factura_pdf_se_puede_descargar(): void
    {
        $this->loginComoAdmin();
        $cuota = Cuota::factory()->create();

        $response = $this->get('/facturas/descargar/'.$cuota->id);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
