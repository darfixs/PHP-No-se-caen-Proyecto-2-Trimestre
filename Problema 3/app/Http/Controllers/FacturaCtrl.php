<?php

/**
 * Controlador para mostrar / descargar la factura en PDF de una cuota.
 *
 * La generación del PDF está en la clase auxiliar App\Clases\FacturaPdf.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Clases\FacturaPdf;
use App\Models\Cuota;

class FacturaCtrl extends Controller
{
    /** Muestra la factura en el navegador (inline, PDF). */
    public function mostrar(Cuota $cuota)
    {
        return FacturaPdf::mostrar($cuota);
    }

    /** Descarga la factura como PDF. */
    public function descargar(Cuota $cuota)
    {
        return FacturaPdf::descargar($cuota);
    }
}
