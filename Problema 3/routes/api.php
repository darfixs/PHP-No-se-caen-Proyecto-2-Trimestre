<?php

/**
 * Rutas de la API REST.
 *
 * Las urls de aquí llevan el prefijo /api automáticamente,
 * así que Route::apiResource('clientes', ...) genera:
 *   GET    /api/clientes           index
 *   POST   /api/clientes           store
 *   GET    /api/clientes/{cli}     show
 *   PUT    /api/clientes/{cli}     update
 *   DELETE /api/clientes/{cli}     destroy
 *
 * @author  Alumno DWES
 * @version 1.0
 */

use App\Http\Controllers\Api\ClientesApiCtrl;
use Illuminate\Support\Facades\Route;

// CRUD completo de clientes vía REST
Route::apiResource('clientes', ClientesApiCtrl::class);

// Países (para el <select>)
Route::get('/paises', [ClientesApiCtrl::class, 'paises']);
