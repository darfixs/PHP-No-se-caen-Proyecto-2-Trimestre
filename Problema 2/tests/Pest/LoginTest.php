<?php


use App\Models\Empleado;

test('login con credenciales correctas redirige a tareas', function () {
    $admin = Empleado::factory()->administrador()->create([
        'correo'   => 'jefe@nosecaen.com',
        'password' => 'secreto123',
    ]);

    $this->post('/login', [
        'correo'   => 'jefe@nosecaen.com',
        'password' => 'secreto123',
    ])->assertRedirect('/tareas');

    $this->assertAuthenticatedAs($admin);
});

test('login con password incorrecta NO inicia sesión', function () {
    Empleado::factory()->create([
        'correo'   => 'juan@nosecaen.com',
        'password' => 'correcta',
    ]);

    $this->from('/login')->post('/login', [
        'correo'   => 'juan@nosecaen.com',
        'password' => 'MAL',
    ])->assertRedirect('/login')
      ->assertSessionHasErrors(['correo']);

    $this->assertGuest();
});

test('login con correo inexistente NO inicia sesión', function () {
    $this->from('/login')->post('/login', [
        'correo'   => 'nadie@ejemplo.com',
        'password' => 'loquesea',
    ])->assertSessionHasErrors(['correo']);

    $this->assertGuest();
});

test('logout cierra la sesión', function () {
    $admin = Empleado::factory()->administrador()->create();
    $this->actingAs($admin);

    $this->post('/logout')->assertRedirect('/login');
    $this->assertGuest();
});
