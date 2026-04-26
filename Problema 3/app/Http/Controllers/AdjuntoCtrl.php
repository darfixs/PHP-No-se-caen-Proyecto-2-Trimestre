<?php

/**
 * Controlador para descargar el fichero resumen que subió el operario
 * al completar una tarea.
 *
 * Usa la clase Upload para acceder al fichero (que está en
 * storage/app/adjuntos/tareas/ y no es accesible por URL directa).
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Clases\Upload;
use App\Models\Tarea;
use Illuminate\Http\Request;

class AdjuntoCtrl extends Controller
{
    /** Descarga el fichero adjunto de una tarea. */
    public function descargar(Request $request, Tarea $tarea)
    {
        $usuario = $request->user();

        // Un operario solo puede ver los ficheros de SUS tareas
        if ($usuario->esOperario() && $tarea->operario_id !== $usuario->id) {
            abort(403);
        }

        if (empty($tarea->ficheroResumen)) {
            abort(404, 'Esta tarea no tiene fichero adjunto.');
        }

        $ruta = Upload::obtenerRutaAbsoluta($tarea->ficheroResumen);
        if (!$ruta) {
            abort(404, 'El fichero no se encuentra en el servidor.');
        }

        // response()->download() fuerza la descarga con el nombre original
        return response()->download($ruta, $tarea->ficheroNombreOriginal);
    }
}
