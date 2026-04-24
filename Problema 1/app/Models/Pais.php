<?php

/**
 * Modelo Eloquent para la entidad Pais.
 *
 * Se usa en el alta/edición de clientes para rellenar el desplegable
 * "País" que pide el PDF.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pais extends Model
{
    /** Nombre real de la tabla (en Laravel por defecto sería "pais") */
    protected $table = 'paises';

    /** Campos asignables en masa. */
    protected $fillable = ['codigo', 'nombre', 'moneda'];

    /** Un país tiene muchos clientes. */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    /* Listado ordenado de todos los países (para el <select>). */
    public static function listadoOrdenado()
    {
        return self::orderBy('nombre')->get();
    }
}
