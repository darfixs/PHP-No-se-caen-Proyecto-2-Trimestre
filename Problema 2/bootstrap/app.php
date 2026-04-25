<?php

/**
 * Fichero de bootstrap de la aplicación Laravel 11+.
 *
 * Aquí se registran las rutas y el middleware personalizado.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

use App\Http\Middleware\SoloAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registro el alias "solo.admin" para usarlo en las rutas
        $middleware->alias([
            'solo.admin' => SoloAdmin::class,
        ]);

        // Redirigir al /login si no está autenticado
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
