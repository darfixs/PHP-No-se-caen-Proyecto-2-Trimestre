<?php

/*
 * Configuración de autenticación.
 *
 * Importante: le digo a Laravel que el modelo que representa al usuario
 * del sistema es App\Models\Empleado (y NO el típico App\Models\User),
 * porque en este proyecto los "usuarios" son empleados.
 *
 * También ajusto la tabla a "empleados".
 */

return [

    'defaults' => [
        'guard'     => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'empleados'),
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'empleados',
        ],
    ],

    'providers' => [
        'empleados' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Empleado::class,
        ],
    ],

    'passwords' => [
        'empleados' => [
            'provider' => 'empleados',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
