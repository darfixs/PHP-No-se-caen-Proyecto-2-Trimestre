<?php

/**
 * Controlador de empleados.
 *
 * Gestiona el CRUD de empleados (solo accesible para administradores)
 * más la opción de que cualquier empleado edite su propio correo y
 * fecha de alta.
 *
 * Toda la lógica de consulta está en el modelo Empleado (como pide
 * el PDF). El controlador se limita a coger datos, llamar al modelo
 * y devolver la vista.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoPerfilRequest;
use App\Http\Requests\EmpleadoRequest;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadosCtrl extends Controller
{
    /** Lista paginada de empleados (solo admin). */
    public function index()
    {
        $empleados = Empleado::listado(10);
        return view('empleados.lista', compact('empleados'));
    }

    /** Formulario de alta. */
    public function crearForm()
    {
        return view('empleados.alta');
    }

    /** Procesa el alta (el FormRequest valida). */
    public function crear(EmpleadoRequest $request)
    {
        Empleado::create($request->validated());
        return redirect('/empleados')->with('ok', 'Empleado creado correctamente.');
    }

    /** Formulario de edición. */
    public function editarForm(Empleado $empleado)
    {
        return view('empleados.editar', compact('empleado'));
    }

    /** Procesa la edición. */
    public function editar(EmpleadoRequest $request, Empleado $empleado)
    {
        $datos = $request->validated();

        // Si no me mandan password, la dejo tal cual
        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $empleado->update($datos);
        return redirect('/empleados')->with('ok', 'Empleado actualizado.');
    }

    /** Confirmación de borrado. */
    public function eliminarConfirm(Empleado $empleado)
    {
        return view('empleados.eliminar_confirm', compact('empleado'));
    }

    /** Borra el empleado. */
    public function eliminar(Empleado $empleado)
    {
        $empleado->delete();
        return redirect('/empleados')->with('ok', 'Empleado eliminado.');
    }

    /*  PERFIL  */

    /** Muestra el formulario para que el empleado edite su propio correo/fecha. */
    public function perfilForm(Request $request)
    {
        $empleado = $request->user();
        return view('empleados.perfil', compact('empleado'));
    }

    /** Procesa la edición del perfil propio. */
    public function perfil(EmpleadoPerfilRequest $request)
    {
        $empleado = $request->user();
        $empleado->update($request->validated());
        return redirect('/perfil')->with('ok', 'Datos actualizados.');
    }
}
