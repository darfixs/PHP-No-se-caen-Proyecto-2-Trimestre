<?php

/**
 * API REST de clientes.
 *
 * Esta API la consumen los TRES apartados del Problema 3:
 *   - 3.1: fetch() desde JS puro
 *   - 3.2: axios desde Vue/Quasar CDN
 *   - 3.3: axios desde Vue+Vite (Inertia puede usarla también para CRUD reactivo)
 *
 * Devuelve JSON. Usa los FormRequests existentes del Problema 1 para validar.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientesApiCtrl extends Controller
{
    /**
     * GET /api/clientes
     *
     * Devuelve TODOS los clientes con su país cargado (eager loading).
     * Los tres apartados llaman aquí para rellenar su tabla.
     */
    public function index(): JsonResponse
    {
        $clientes = Cliente::with('pais')->orderBy('nombre')->get();
        return response()->json($clientes);
    }

    /**
     * GET /api/clientes/{cliente}
     *
     * Devuelve un cliente concreto para precargar el formulario de edición.
     */
    public function show(Cliente $cliente): JsonResponse
    {
        $cliente->load('pais');
        return response()->json($cliente);
    }

    /**
     * POST /api/clientes
     *
     * Crea un cliente nuevo. Reutiliza el ClienteRequest del Problema 1
     * para tener la misma validación que el CRUD Blade.
     *
     * Si la validación falla, Laravel devuelve automáticamente un 422
     * con los errores en JSON (porque la petición es JSON).
     */
    public function store(ClienteRequest $request): JsonResponse
    {
        $cliente = Cliente::create($request->validated());
        $cliente->load('pais');
        return response()->json($cliente, 201); // 201 Created
    }

    /**
     * PUT /api/clientes/{cliente}
     */
    public function update(ClienteRequest $request, Cliente $cliente): JsonResponse
    {
        $cliente->update($request->validated());
        $cliente->load('pais');
        return response()->json($cliente);
    }

    /**
     * DELETE /api/clientes/{cliente}
     *
     * Respeta la regla Opción A: si el cliente tiene tareas, NO se borra.
     */
    public function destroy(Cliente $cliente): JsonResponse
    {
        if ($cliente->tareas()->count() > 0) {
            return response()->json([
                'error' => 'No se puede eliminar: el cliente tiene tareas asociadas.',
            ], 409); // 409 Conflict
        }

        $cliente->delete();
        return response()->json(['ok' => true]); // 200 OK
    }

    /**
     * GET /api/paises
     *
     * Lista de países (para el <select> del formulario).
     */
    public function paises(): JsonResponse
    {
        return response()->json(Pais::orderBy('nombre')->get());
    }
}
