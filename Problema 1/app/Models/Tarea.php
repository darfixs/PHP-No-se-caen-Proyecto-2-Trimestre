<?php

/**
 * Modelo Eloquent para la entidad Tarea.
 *
 * Representa una incidencia / orden de trabajo. Siguiendo el PDF,
 * aquí vive TODA la lógica de consulta (listar, filtrar, completar, etc.)
 * para no dejarla en los controladores.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    protected $table = 'tareas';

    /** Uso mi propio nombre para la fecha de creación automática. */
    const CREATED_AT = 'fechaCreacion';

    /** No quiero timestamp de "updated_at" (no lo pide el PDF). */
    const UPDATED_AT = null;

    protected $fillable = [
        'cliente_id', 'personaNombre', 'telefono', 'correo',
        'descripcionTarea', 'direccionTarea', 'poblacion',
        'codigoPostal', 'provincia', 'estadoTarea', 'operario_id',
        'fechaRealizacion', 'anotacionesAnteriores',
        'anotacionesPosteriores', 'ficheroResumen', 'ficheroNombreOriginal',
    ];

    protected $casts = [
        'fechaRealizacion' => 'date',
        'fechaCreacion'    => 'datetime',
    ];

    /*  RELACIONES
    /** Cada tarea pertenece a un cliente. */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /** Cada tarea pertenece (puede pertenecer) a un operario. */
    public function operario(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'operario_id');
    }

    /** Relación con provincia (por código INE). */
    public function provinciaRel(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia', 'codigo');
    }

    /*  CONSULTAS  */
    public static function listadoParaAdmin(int $porPagina = 10)
    {
        return self::with(['cliente', 'operario'])
                   ->orderBy('fechaCreacion', 'desc')
                   ->paginate($porPagina);
    }

    public static function listadoParaOperario(int $operarioId, int $porPagina = 10)
    {
        return self::with(['cliente'])
                   ->where('operario_id', $operarioId)
                   ->orderBy('fechaCreacion', 'desc')
                   ->paginate($porPagina);
    }

    public function completar(array $datos,
                              ?string $rutaFichero = null,
                              ?string $nombreOriginal = null): void
    {
        $this->estadoTarea            = $datos['estadoTarea']            ?? 'R';
        $this->anotacionesPosteriores = $datos['anotacionesPosteriores'] ?? '';

        if ($rutaFichero !== null) {
            $this->ficheroResumen        = $rutaFichero;
            $this->ficheroNombreOriginal = $nombreOriginal;
        }

        $this->save();
    }
}
