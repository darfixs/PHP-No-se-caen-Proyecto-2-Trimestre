<?php

/**
 * Fichero de bootstrap de la aplicación Laravel 11+.
 *
 * Respecto al Problema 2, añado:
 *   - Registro de routes/api.php (necesario para la API REST del 3.1-3.3).
 *   - Middleware HandleInertiaRequests (para el 3.3).
 *
 * @author  Alumno DWES
 * @version 1.2
 */

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SoloAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',    // API REST del Problema 3
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias para las rutas solo-admin
        $middleware->alias([
            'solo.admin' => SoloAdmin::class,
        ]);

        // Redirigir al /login si no hay sesión
        $middleware->redirectGuestsTo('/login');

        // Inertia: añadir su middleware a la pila "web" (Problema 3.3)
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
