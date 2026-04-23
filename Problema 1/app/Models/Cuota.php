<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuota extends Model
{
    protected $fillable = [
        'cliente_id', 'concepto', 'fecha_emision', 'importe', 'moneda',
        'pagada', 'fecha_pago', 'notas',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_pago'    => 'date',
        'pagada'        => 'boolean',
        'importe'       => 'decimal:2',
    ];

    /* RELACIONES  */

    /* Cada cuota pertenece a un cliente. */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /* LÓGICA DE NEGOCIO / CONSULTAS */
    public static function listado(int $porPagina = 10)
{
        return self::with('cliente')
                   ->orderBy('fecha_emision', 'desc')
                   ->paginate($porPagina);
    }

    /**
     * Genera la remesa mensual: crea una cuota para TODOS los clientes,
     * usando el importe que tengan configurado.

     * Este método es el que se llama cuando el admin pulsa
     * "Generar remesa mensual" (PDF: "añadir remesa mensual a todos los clientes")
     */
    public static function generarRemesaMensual(?string $concepto = null): int
    {
        $fechaEmision = now()->toDateString();
        $conceptoFinal = $concepto ?? 'Cuota mensual '.now()->format('m/Y');
        $contador = 0;

        // Saco todos los clientes en una sola query
        $clientes = Cliente::all();

        foreach ($clientes as $cliente) {
            self::create([
                'cliente_id'    => $cliente->id,
                'concepto'      => $conceptoFinal,
                'fecha_emision' => $fechaEmision,
                'importe'       => $cliente->importe_cuota,
                'moneda'        => $cliente->moneda,
                'pagada'        => false,
            ]);
            $contador++;
        }

        return $contador;
    }
}
