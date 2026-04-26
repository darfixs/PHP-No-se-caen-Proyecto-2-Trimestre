<?php

/**
 * Controlador para que el OPERARIO complete una tarea:
 *  - Cambia el estado.
 *  - Añade anotaciones posteriores.
 *  - Adjunta un fichero resumen (opcional).
 *
 * La lógica de guardado vive en el modelo Tarea (método completar())
 * y en la clase auxiliar Upload.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Clases\Upload;
use App\Http\Requests\CompletarTareaRequest;
use App\Models\Tarea;
use Illuminate\Http\Request;

class CompletarCtrl extends Controller
{
    /** Muestra el formulario de completar. */
    public function formulario(Request $request, Tarea $tarea)
    {
        $usuario = $request->user();

        // Un operario solo puede completar sus propias tareas
        if ($usuario->esOperario() && $tarea->operario_id !== $usuario->id) {
            abort(403, 'Esta tarea no te pertenece.');
        }

        return view('tareas.completar', compact('tarea'));
    }

    /** Procesa el completado. */
    public function completar(CompletarTareaRequest $request, Tarea $tarea)
    {
        $usuario = $request->user();

        // Verifico otra vez por seguridad
        if ($usuario->esOperario() && $tarea->operario_id !== $usuario->id) {
            abort(403, 'Esta tarea no te pertenece.');
        }

        $rutaFichero    = null;
        $nombreOriginal = null;

        // Si ha subido un fichero → lo guardo con la clase Upload
        if ($request->hasFile('ficheroResumen')) {
            $info = Upload::guardarAdjunto(
                $request->file('ficheroResumen'),
                $tarea->id
            );
            $rutaFichero    = $info['ruta'];
            $nombreOriginal = $info['nombreOriginal'];
        }

        // Delego el guardado en el modelo
        $tarea->completar($request->validated(), $rutaFichero, $nombreOriginal);

        return redirect('/tareas')->with('ok', 'Tarea completada correctamente.');
    }
}
