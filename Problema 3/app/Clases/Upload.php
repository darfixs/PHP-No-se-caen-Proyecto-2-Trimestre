<?php

/**
 * Clase auxiliar Upload.
 *
 * Encapsula el guardado del fichero resumen que adjunta el operario
 * cuando completa una tarea.
 *
 * El PDF dice: "Dicho fichero se almacenará en una carpeta dentro del
 * servidor y no será accesible fácilmente", así que lo guardamos en
 * `storage/app/adjuntos/tareas/` (fuera de /public).
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Clases;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Upload
{
    /** Carpeta donde se guardan los adjuntos (relativa a storage/app). */
    private const CARPETA = 'adjuntos/tareas';

    /**
     * Guarda el fichero subido y devuelve la ruta relativa y el nombre original.
     *
     * @param  UploadedFile $fichero
     * @param  int          $tareaId Id de la tarea (para componer el nombre).
     * @return array{ruta: string, nombreOriginal: string}
     */
    public static function guardarAdjunto(UploadedFile $fichero, int $tareaId): array
    {
        // Nombre original que subió el usuario
        $nombreOriginal = $fichero->getClientOriginalName();

        // Compongo un nombre único para evitar colisiones: tarea_{id}_{timestamp}.{ext}
        $extension = $fichero->getClientOriginalExtension();
        $nuevoNombre = 'tarea_'.$tareaId.'_'.time().'.'.$extension;

        // Guardo el fichero (el "disk" 'local' apunta a storage/app por defecto)
        $ruta = $fichero->storeAs(self::CARPETA, $nuevoNombre, 'local');

        return [
            'ruta'           => $ruta,
            'nombreOriginal' => $nombreOriginal,
        ];
    }

    /**
     * Devuelve el contenido binario de un fichero guardado (para descargarlo).
     *
     * @param  string $ruta
     * @return string|null  null si no existe
     */
    public static function obtenerContenido(string $ruta): ?string
    {
        if (!Storage::disk('local')->exists($ruta)) {
            return null;
        }
        return Storage::disk('local')->get($ruta);
    }

    /**
     * Devuelve la ruta absoluta en el servidor (para response()->file()).
     *
     * @param  string $ruta
     * @return string|null
     */
    public static function obtenerRutaAbsoluta(string $ruta): ?string
    {
        if (!Storage::disk('local')->exists($ruta)) {
            return null;
        }
        return Storage::disk('local')->path($ruta);
    }
}
