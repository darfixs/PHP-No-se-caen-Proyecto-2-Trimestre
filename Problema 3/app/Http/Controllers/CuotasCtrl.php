<?php

/**
 * Controlador de cuotas.
 *
 * Cubre:
 *  - CRUD de cuotas (admin).
 *  - Remesa mensual: crea una cuota para todos los clientes.
 *  - Envío por correo de la factura en PDF al cliente.
 *
 * Toda la lógica "gorda" vive en el modelo Cuota y en las clases
 * auxiliares FacturaPdf / CuotaCreadaMail.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\CuotaRequest;
use App\Mail\CuotaCreadaMail;
use App\Models\Cliente;
use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CuotasCtrl extends Controller
{
    /** Listado paginado de cuotas. */
    public function index()
    {
        $cuotas = Cuota::listado(10);
        return view('cuotas.lista', compact('cuotas'));
    }

    /** Formulario de alta (cuota excepcional). */
    public function crearForm()
    {
        $clientes = Cliente::listadoOrdenado();
        return view('cuotas.alta', compact('clientes'));
    }

    /** Procesa el alta. */
    public function crear(CuotaRequest $request)
    {
        $cuota = Cuota::create($request->validated());

        // Al crear una cuota, el PDF pide mandar correo con la factura al cliente
        $this->enviarFacturaEmail($cuota);

        return redirect('/cuotas')->with('ok', 'Cuota creada y factura enviada al cliente.');
    }

    /** Formulario de edición. */
    public function editarForm(Cuota $cuota)
    {
        $clientes = Cliente::listadoOrdenado();
        return view('cuotas.editar', compact('cuota', 'clientes'));
    }

    /** Procesa la edición. */
    public function editar(CuotaRequest $request, Cuota $cuota)
    {
        $cuota->update($request->validated());
        return redirect('/cuotas')->with('ok', 'Cuota actualizada.');
    }

    /** Confirmar borrado. */
    public function eliminarConfirm(Cuota $cuota)
    {
        return view('cuotas.eliminar_confirm', compact('cuota'));
    }

    /** Borrar. */
    public function eliminar(Cuota $cuota)
    {
        $cuota->delete();
        return redirect('/cuotas')->with('ok', 'Cuota eliminada.');
    }

    /* =====================================================================
     *  REMESA MENSUAL
     * ===================================================================== */

    /**
     * Genera una cuota para TODOS los clientes y envía el correo con la
     * factura a cada uno.
     */
    public function remesaMensual()
    {
        $total = Cuota::generarRemesaMensual();

        // Envío correo de cada cuota recién creada.
        // Cojo las cuotas creadas hoy (son las de la remesa) y les mando email.
        $creadasHoy = Cuota::with('cliente')
                           ->whereDate('fecha_emision', now()->toDateString())
                           ->get();

        foreach ($creadasHoy as $cuota) {
            $this->enviarFacturaEmail($cuota);
        }

        return redirect('/cuotas')->with('ok',
            "Remesa mensual generada. Se crearon {$total} cuotas y se enviaron los correos.");
    }

    /* =====================================================================
     *  ENVÍO INDIVIDUAL DE FACTURA POR CORREO
     * ===================================================================== */

    /** Reenviar manualmente el correo con la factura (por si falló). */
    public function reenviarEmail(Cuota $cuota)
    {
        $this->enviarFacturaEmail($cuota);
        return redirect('/cuotas')->with('ok', 'Factura reenviada.');
    }

    /**
     * Helper privado que manda el correo.
     * Se envuelve en try/catch para que si falla el servidor SMTP
     * la aplicación siga funcionando.
     */
    private function enviarFacturaEmail(Cuota $cuota): void
    {
        try {
            $cuota->loadMissing('cliente');
            Mail::to($cuota->cliente->correo)->send(new CuotaCreadaMail($cuota));
        } catch (\Throwable $e) {
            // Lo loggeo pero no rompo la aplicación
            \Log::warning('No se pudo enviar el correo de la cuota '.$cuota->id.': '.$e->getMessage());
        }
    }
}
