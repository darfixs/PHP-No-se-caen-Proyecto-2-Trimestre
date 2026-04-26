<?php

/**
 * Middleware de Inertia.
 *
 * Inertia necesita un middleware que comparta datos comunes con
 * todos los componentes Vue en cada petición. Aquí le paso:
 *   - El usuario autenticado (para enseñarlo en la UI).
 *   - Los mensajes flash (ok/error) de la sesión.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /** Plantilla Blade que envuelve al SPA Inertia. */
    protected $rootView = 'app';

    /**
     * Datos compartidos automáticamente con todos los componentes Vue.
     * Accesibles en cualquier Page.vue como $page.props.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            // Usuario autenticado (o null si no hay sesión)
            'auth' => [
                'user' => $request->user()
                    ? [
                        'id'     => $request->user()->id,
                        'nombre' => $request->user()->nombre,
                        'correo' => $request->user()->correo,
                        'tipo'   => $request->user()->tipo,
                    ]
                    : null,
            ],

            // Mensajes flash: Inertia los pasa a Vue en cada render
            'flash' => [
                'ok'    => fn () => $request->session()->get('ok'),
                'error' => fn () => $request->session()->get('error'),
            ],

            // Token CSRF (por si axios lo necesita desde un componente .vue)
            'csrf_token' => csrf_token(),
        ];
    }
}
