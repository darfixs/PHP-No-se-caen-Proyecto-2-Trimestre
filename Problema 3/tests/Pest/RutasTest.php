<?php

/**
 * Tests de rutas con estilo Pest.
 *
 * Equivalente a RutasPublicasTest + RutasAutenticadasTest pero
 * escrito en estilo Pest (más legible).
 */

use App\Models\Empleado;

test('la raíz redirige al login si no hay sesión', function () {
    $this->get('/')->assertRedirect('/login');
});

test('el formulario de login se muestra', function () {
    $this->get('/login')->assertOk()->assertSee('Iniciar sesión');
});

test('el formulario de incidencia pública se muestra', function () {
    $this->get('/incidencia')->assertOk()->assertSee('Reportar una incidencia');
});

test('sin sesión las rutas protegidas redirigen al login', function (string $url) {
    $this->get($url)->assertRedirect('/login');
})->with([
    '/tareas',
    '/empleados',
    '/clientes',
    '/cuotas',
    '/perfil',
]);

test('el admin puede entrar a todos los listados', function (string $url, string $textoEsperado) {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);
    $this->get($url)->assertOk()->assertSee($textoEsperado);
})->with([
    ['/empleados', 'Listado de empleados'],
    ['/clientes',  'Listado de clientes'],
    ['/cuotas',    'Listado de cuotas'],
    ['/perfil',    'Mi perfil'],
]);

test('un operario NO puede entrar a rutas de admin', function (string $url) {
    $op = Empleado::factory()->create(); // Operario por defecto
    $this->actingAs($op);
    $this->get($url)->assertForbidden();
})->with([
    '/empleados',
    '/clientes',
    '/cuotas',
    '/empleados/crear',
    '/tareas/crear',
]);
