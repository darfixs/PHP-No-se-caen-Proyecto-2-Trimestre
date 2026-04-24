<?php

/**
 * solo deja pasar a los empleados de tipo Administrador.
 *
 * Se aplica a las rutas donde el PDF dice "solo administradores":
 *  - Gestión de empleados
 *  - Gestión de clientes
 *  - Gestión de cuotas
 *  - Creación/edición/borrado de tareas
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoloAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = $request->user();

        // Si no está logueado o no es admin, lo mando al listado de tareas
        // con un mensaje de error (uso "abort" para que sea claro).
        if (!$usuario || !$usuario->esAdmin()) {
            abort(403, 'Acceso restringido: solo administradores.');
        }

        return $next($request);
    }
}
