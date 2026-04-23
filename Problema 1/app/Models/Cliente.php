<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'cif', 'nombre', 'telefono', 'correo', 'cuenta_corriente',
        'pais_id', 'moneda', 'importe_cuota',
    ];

    protected $casts = [
        'importe_cuota' => 'decimal:2',
    ];

    /* RELACIONES */

    /* Cada cliente pertenece a un país. */
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    /* Un cliente tiene muchas cuotas. */
    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class);
    }

    /* Un cliente tiene muchas tareas. */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    /* LÓGICA DE NEGOCIO / CONSULTAS */
    public static function listado(int $porPagina = 10)
    {
        return self::with('pais')->orderBy('nombre')->paginate($porPagina);
    }

    /* Listado completo (sin paginar) para selects. */
    public static function listadoOrdenado()
    {
        return self::orderBy('nombre')->get();
    }


    public static function autenticarPorCifTelefono(string $cif, string $telefono): ?self
    {
        return self::where('cif', $cif)
                   ->where('telefono', $telefono)
                   ->first();
    }
}
