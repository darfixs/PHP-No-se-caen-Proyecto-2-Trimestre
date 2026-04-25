<?php

/**
 * Modelo Eloquent para la entidad Cliente.
 *
 * Un cliente es una empresa a la que prestamos servicio de mantenimiento.
 * Tiene muchas cuotas y muchas tareas.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cif', 'nombre', 'telefono', 'correo', 'cuenta_corriente',
        'pais_id', 'moneda', 'importe_cuota',
    ];

    protected $casts = [
        'importe_cuota' => 'decimal:2',
    ];

    /* RELACIONES 

    /** Cada cliente pertenece a un país. */
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    /** Un cliente tiene muchas cuotas. */
    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class);
    }

    /** Un cliente tiene muchas tareas. */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    /* LÓGICA DE NEGOCIO / CONSULTAS */

    /**
     * Lista paginada de clientes con su país ya cargado (eager loading).
     *
     * @param  int $porPagina
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function listado(int $porPagina = 10)
    {
        return self::with('pais')->orderBy('nombre')->paginate($porPagina);
    }

    /**
     * Listado completo (sin paginar) para selects.
     */
    public static function listadoOrdenado()
    {
        return self::orderBy('nombre')->get();
    }

    /**
     * Busca un cliente comprobando CIF y teléfono (requisito del PDF:
     * "Para garantizar la identidad de los clientes se solicitará
     *  al cliente que introduzca su CIF y teléfono, los cuales deben
     *  coincidir con los registrados").
     *
     * @param  string $cif
     * @param  string $telefono
     * @return self|null
     */
    public static function autenticarPorCifTelefono(string $cif, string $telefono): ?self
    {
        return self::where('cif', $cif)
                   ->where('telefono', $telefono)
                   ->first();
    }
}
