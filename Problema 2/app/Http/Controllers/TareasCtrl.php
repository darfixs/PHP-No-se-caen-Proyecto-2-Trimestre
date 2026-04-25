<?php

/**
 * Controlador de tareas (incidencias).
 *
 * - Admin: ve todas, crea, edita, borra, asigna operario.
 * - Operario: solo ve las suyas y puede completarlas.
 *
 * La lógica de consulta (diferencia admin/operario) vive en el modelo Tarea.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\TareaRequest;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Provincia;
use App\Models\Tarea;
use Illuminate\Http\Request;

class TareasCtrl extends Controller
{
    /**
     * Listado de tareas.
     *
     * Diferenciamos el listado según el rol:
     *  - admin → TODAS
     *  - operario → SOLO las asignadas a él (PDF)
     */
    public function index(Request $request)
    {
        $usuario = $request->user();

        if ($usuario->esAdmin()) {
            $tareas = Tarea::listadoParaAdmin(10);
        } else {
            $tareas = Tarea::listadoParaOperario($usuario->id, 10);
        }

        return view('tareas.lista', compact('tareas'));
    }

    /** Detalle de una tarea. */
    public function detalle(Request $request, Tarea $tarea)
    {
        $usuario = $request->user();

        // Si es operario y esta tarea NO es suya, le corto el acceso
        if ($usuario->esOperario() && $tarea->operario_id !== $usuario->id) {
            abort(403, 'Esta tarea no te pertenece.');
        }

        $tarea->load(['cliente', 'operario', 'provinciaRel']);
        return view('tareas.detalle', compact('tarea'));
    }

    /** Formulario de alta. */
    public function crearForm()
    {
        return view('tareas.alta', $this->datosComunesFormulario());
    }

    /** Procesa el alta. */
    public function crear(TareaRequest $request)
    {
        Tarea::create($request->validated());
        return redirect('/tareas')->with('ok', 'Tarea creada.');
    }

    /** Formulario de edición. */
    public function editarForm(Tarea $tarea)
    {
        return view('tareas.editar',
            array_merge($this->datosComunesFormulario(), ['tarea' => $tarea])
        );
    }

    /** Procesa la edición. */
    public function editar(TareaRequest $request, Tarea $tarea)
    {
        $tarea->update($request->validated());
        return redirect('/tareas')->with('ok', 'Tarea actualizada.');
    }

    /** Confirmación de borrado. */
    public function eliminarConfirm(Tarea $tarea)
    {
        return view('tareas.eliminar_confirm', compact('tarea'));
    }

    /** Borra la tarea. */
    public function eliminar(Tarea $tarea)
    {
        $tarea->delete();
        return redirect('/tareas')->with('ok', 'Tarea eliminada.');
    }

    /**
     * Datos comunes que necesitan los formularios de alta y edición:
     * lista de clientes, operarios y provincias para los <select>.
     */
    private function datosComunesFormulario(): array
    {
        return [
            'clientes'   => Cliente::listadoOrdenado(),
            'operarios'  => Empleado::operarios(),
            'provincias' => Provincia::listadoOrdenado(),
        ];
    }
}
