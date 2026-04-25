<?php

/**
 * Modelo Eloquent para la entidad Provincia.
 *
 * Se usa en el alta/edición de tareas para el <select> de provincia.
 * La PK es el "codigo" (2 dígitos INE, no un id autonumérico).
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $table      = 'provincias';
    protected $primaryKey = 'codigo';     // la PK no es id, es código INE
    public    $incrementing = false;      // y no es autoincremental
    protected $keyType    = 'string';     // es string, no int
    public    $timestamps = false;        // esta tabla no lleva timestamps

    protected $fillable = ['codigo', 'nombre'];

    /**
     * Listado ordenado (alfabéticamente) para los desplegables.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function listadoOrdenado()
    {
        return self::orderBy('nombre')->get();
    }
}
