<?php

/**
 * Controlador de clientes (CRUD).
 *
 * Solo admin. La lógica de consulta vive en el modelo Cliente.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Pais;

class ClientesCtrl extends Controller
{
    /** Listado paginado. */
    public function index()
    {
        $clientes = Cliente::listado(10);
        return view('clientes.lista', compact('clientes'));
    }

    /** Formulario de alta. */
    public function crearForm()
    {
        // Le paso los países para que el <select> del PDF los muestre
        $paises = Pais::listadoOrdenado();
        return view('clientes.alta', compact('paises'));
    }

    /** Procesa el alta. */
    public function crear(ClienteRequest $request)
    {
        Cliente::create($request->validated());
        return redirect('/clientes')->with('ok', 'Cliente creado.');
    }

    /** Formulario de edición. */
    public function editarForm(Cliente $cliente)
    {
        $paises = Pais::listadoOrdenado();
        return view('clientes.editar', compact('cliente', 'paises'));
    }

    /** Procesa la edición. */
    public function editar(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect('/clientes')->with('ok', 'Cliente actualizado.');
    }

    /** Confirmación de borrado. */
    public function eliminarConfirm(Cliente $cliente)
    {
        return view('clientes.eliminar_confirm', compact('cliente'));
    }

    /** Borra el cliente. */
    public function eliminar(Cliente $cliente)
    {
        $cliente->delete();
        return redirect('/clientes')->with('ok', 'Cliente eliminado.');
    }
}
