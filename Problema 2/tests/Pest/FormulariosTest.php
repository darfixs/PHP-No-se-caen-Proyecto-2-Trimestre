<?php

/**
 * Tests Pest: envío de formularios (lo que pide el PDF).
 *
 * Cubro el flujo "alta + validación + borrado" de las entidades
 * principales en estilo Pest.
 */

use App\Mail\CuotaCreadaMail;
use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Empleado;
use App\Models\Pais;
use App\Models\Tarea;
use Illuminate\Support\Facades\Mail;

/* EMPLEADOS */

test('el admin puede crear un empleado', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $this->post('/empleados/crear', [
        'dni'        => '99999999Z',
        'nombre'     => 'Nuevo Operario',
        'correo'     => 'nuevo@nosecaen.com',
        'fecha_alta' => now()->toDateString(),
        'tipo'       => 'Operario',
        'password'   => 'clavetest',
    ])->assertRedirect('/empleados');

    $this->assertDatabaseHas('empleados', ['dni' => '99999999Z']);
});

test('crear empleado sin nombre falla la validación', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $this->from('/empleados/crear')->post('/empleados/crear', [
        'dni'        => '11111111A',
        'correo'     => 'x@x.com',
        'fecha_alta' => now()->toDateString(),
        'tipo'       => 'Operario',
        'password'   => 'clave',
    ])->assertSessionHasErrors('nombre');
});

/* CLIENTES (incluye la Opción A) */

test('cliente sin tareas se puede borrar', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $cliente = Cliente::factory()->create();

    $this->post('/clientes/eliminar/'.$cliente->id)->assertRedirect('/clientes');
    $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
});

test('cliente con tareas NO se puede borrar', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $cliente = Cliente::factory()->create();
    Tarea::factory()->create(['cliente_id' => $cliente->id]);

    $this->post('/clientes/eliminar/'.$cliente->id)
         ->assertRedirect('/clientes')
         ->assertSessionHas('error');

    $this->assertDatabaseHas('clientes', ['id' => $cliente->id]);
});

/* TAREAS (reglas clave del PDF) */

test('admin no puede crear tarea sin operario', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);
    $cliente = Cliente::factory()->create();

    $this->from('/tareas/crear')->post('/tareas/crear', [
        'cliente_id'        => $cliente->id,
        'personaNombre'     => 'Contacto',
        'telefono'          => '600000000',
        'correo'            => 'c@c.com',
        'descripcionTarea'  => 'Revisar ascensor',
        'estadoTarea'       => 'P',
        // operario_id omitido
        'fechaRealizacion'  => now()->addDays(3)->toDateString(),
    ])->assertSessionHasErrors('operario_id');
});

test('operario solo ve SUS tareas', function () {
    $opA = Empleado::factory()->create();
    $opB = Empleado::factory()->create();

    Tarea::factory()->count(2)->create(['operario_id' => $opA->id]);
    Tarea::factory()->count(3)->create(['operario_id' => $opB->id]);

    $lista = Tarea::listadoParaOperario($opA->id, 10);
    expect($lista)->toHaveCount(2);
});

/* CUOTAS (remesa mensual + envío de correo) */

test('la remesa mensual crea una cuota por cliente y manda correo', function () {
    Mail::fake();

    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $pais = Pais::factory()->create();
    Cliente::factory()->count(3)->create(['pais_id' => $pais->id]);

    $this->post('/cuotas/remesa')->assertRedirect('/cuotas');
    $this->assertDatabaseCount('cuotas', 3);

    Mail::assertSent(CuotaCreadaMail::class, 3);
});

test('el modelo Cuota puede generar remesa sola', function () {
    $pais = Pais::factory()->create();
    Cliente::factory()->count(4)->create([
        'pais_id'       => $pais->id,
        'importe_cuota' => 150,
    ]);

    $total = Cuota::generarRemesaMensual('Cuota abril');
    expect($total)->toBe(4);
    $this->assertDatabaseCount('cuotas', 4);
});

/* INCIDENCIA PÚBLICA (autenticación por CIF + tlf) */

test('cliente con CIF y teléfono correctos crea incidencia sin operario', function () {
    Cliente::factory()->create([
        'cif'      => 'B99999999',
        'telefono' => '959000111',
    ]);

    $this->post('/incidencia', [
        'cif'              => 'B99999999',
        'telefonoCliente'  => '959000111',
        'personaNombre'    => 'Juan',
        'telefono'         => '600123456',
        'correo'           => 'juan@x.com',
        'descripcionTarea' => 'Ascensor parado',
        'fechaRealizacion' => now()->addDays(3)->toDateString(),
    ])->assertOk();

    $this->assertDatabaseHas('tareas', [
        'operario_id' => null,
        'estadoTarea' => 'P',
    ]);
});

test('CIF correcto pero teléfono erróneo NO crea incidencia', function () {
    Cliente::factory()->create([
        'cif'      => 'B99999999',
        'telefono' => '959000111',
    ]);

    $this->from('/incidencia')->post('/incidencia', [
        'cif'              => 'B99999999',
        'telefonoCliente'  => 'MALO',
        'personaNombre'    => 'Juan',
        'telefono'         => '600123456',
        'correo'           => 'juan@x.com',
        'descripcionTarea' => 'X',
        'fechaRealizacion' => now()->addDays(3)->toDateString(),
    ])->assertSessionHasErrors('cif');

    $this->assertDatabaseCount('tareas', 0);
});
