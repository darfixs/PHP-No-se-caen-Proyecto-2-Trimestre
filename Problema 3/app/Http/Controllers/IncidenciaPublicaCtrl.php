<?php

/**
 * Controlador para que un CLIENTE (sin estar logueado) pueda
 * registrar una incidencia, tal y como pide el PDF:
 *
 *   "Los clientes podrán registrar una incidencia introduciendo los
 *    mismos datos que un administrador con la restricción de que no
 *    asignarán el operario que realizará dicha tarea. En este caso
 *    quedará sin marcar y luego será un administrador el que tendrá
 *    que realizar dicha asignación.
 *    Para garantizar la identidad de los clientes y que terceros no
 *    realicen anotaciones que no proceden se solicitará al cliente
 *    que introduzca su CIF y teléfono, los cuales deben coincidir
 *    con los registrados."
 *
 * La autenticación (CIF + teléfono) se delega al modelo Cliente.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\IncidenciaClienteRequest;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Tarea;

class IncidenciaPublicaCtrl extends Controller
{
    /** Muestra el formulario público. */
    public function formulario()
    {
        $provincias = Provincia::listadoOrdenado();
        return view('incidencias_publicas.alta', compact('provincias'));
    }

    /**
     * Procesa el envío. Primero verifica que CIF + teléfono coinciden
     * con un cliente registrado; si no, rechaza el envío.
     */
    public function registrar(IncidenciaClienteRequest $request)
    {
        $datos = $request->validated();

        // Autenticación del cliente por CIF + teléfono
        $cliente = Cliente::autenticarPorCifTelefono(
            $datos['cif'],
            $datos['telefonoCliente']
        );

        if (!$cliente) {
            // Vuelvo al formulario con un error claro
            return back()
                ->withInput()
                ->withErrors([
                    'cif' => 'El CIF y teléfono no coinciden con ningún cliente registrado.',
                ]);
        }

        // Creo la tarea SIN operario asignado (queda pendiente de asignar)
        Tarea::create([
            'cliente_id'           => $cliente->id,
            'personaNombre'        => $datos['personaNombre'],
            'telefono'             => $datos['telefono'],
            'correo'               => $datos['correo'],
            'descripcionTarea'     => $datos['descripcionTarea'],
            'direccionTarea'       => $datos['direccionTarea']       ?? null,
            'poblacion'            => $datos['poblacion']            ?? null,
            'codigoPostal'         => $datos['codigoPostal']         ?? null,
            'provincia'            => $datos['provincia']            ?? null,
            'estadoTarea'          => 'P', // Pendiente por defecto
            'operario_id'          => null, // clave: NO se asigna operario
            'fechaRealizacion'     => $datos['fechaRealizacion'],
            'anotacionesAnteriores'=> $datos['anotacionesAnteriores'] ?? null,
        ]);

        return view('incidencias_publicas.gracias');
    }
}
