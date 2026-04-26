<?php

/**
 * Clase auxiliar para generar la factura en PDF de una cuota.
 *
 * Usa DomPDF (paquete "barryvdh/laravel-dompdf") que es el más usado
 * en Laravel. Renderiza una vista Blade con los datos y la convierte
 * a PDF.
 *
 * Se pone aquí (en app/Clases/) en lugar de meter el código directamente
 * en el controlador, para que la lógica de generación de PDF esté
 * centralizada y sea fácil de mantener.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Clases;

use App\Models\Cuota;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaPdf
{
    /**
     * Genera el PDF de una cuota y lo devuelve como "binary string".
     * Así luego se puede adjuntar a un correo o volcar al navegador.
     *
     * @param  Cuota $cuota
     * @return string contenido binario del PDF
     */
    public static function generar(Cuota $cuota): string
    {
        // Cargo la relación cliente (y su país) para que aparezcan en la factura
        $cuota->loadMissing('cliente.pais');

        // Renderizo la vista Blade → la convierto a PDF
        $pdf = Pdf::loadView('cuotas.factura_pdf', [
            'cuota'   => $cuota,
            'cliente' => $cuota->cliente,
        ]);

        return $pdf->output();
    }

    /**
     * Igual que generar() pero devuelve el PDF ya preparado para descargar
     * desde el navegador (con nombre de fichero).
     *
     * @param  Cuota $cuota
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function descargar(Cuota $cuota)
    {
        $cuota->loadMissing('cliente.pais');

        $pdf = Pdf::loadView('cuotas.factura_pdf', [
            'cuota'   => $cuota,
            'cliente' => $cuota->cliente,
        ]);

        $nombre = 'factura_'.$cuota->id.'_'.$cuota->cliente->cif.'.pdf';
        return $pdf->download($nombre);
    }

    /**
     * Muestra el PDF inline en el navegador (sin forzar descarga).
     *
     * @param  Cuota $cuota
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function mostrar(Cuota $cuota)
    {
        $cuota->loadMissing('cliente.pais');

        $pdf = Pdf::loadView('cuotas.factura_pdf', [
            'cuota'   => $cuota,
            'cliente' => $cuota->cliente,
        ]);

        return $pdf->stream('factura_'.$cuota->id.'.pdf');
    }
}
