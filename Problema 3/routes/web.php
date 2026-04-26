<?php

/*
|--------------------------------------------------------------------------
| Rutas web del proyecto "Nosecaen S.L."
|--------------------------------------------------------------------------
| Incluye las del Problema 1 (sin cambios) y añade las del Problema 3.
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdjuntoCtrl;
use App\Http\Controllers\ClientesCtrl;
use App\Http\Controllers\CompletarCtrl;
use App\Http\Controllers\CuotasCtrl;
use App\Http\Controllers\EmpleadosCtrl;
use App\Http\Controllers\FacturaCtrl;
use App\Http\Controllers\IncidenciaPublicaCtrl;
use App\Http\Controllers\LoginCtrl;
use App\Http\Controllers\Problema3Ctrl;
use App\Http\Controllers\TareasCtrl;

/* =========================================================================
 *  RUTAS PÚBLICAS
 * ========================================================================= */
Route::get('/', function () {
    return auth()->check() ? redirect('/tareas') : redirect('/login');
});

Route::get('/login',  [LoginCtrl::class, 'formulario'])->name('login');
Route::post('/login', [LoginCtrl::class, 'login']);
Route::post('/logout', [LoginCtrl::class, 'logout'])->name('logout');

Route::get('/incidencia',  [IncidenciaPublicaCtrl::class, 'formulario']);
Route::post('/incidencia', [IncidenciaPublicaCtrl::class, 'registrar']);


/* =========================================================================
 *  RUTAS AUTENTICADAS
 * ========================================================================= */
Route::middleware('auth')->group(function () {

    Route::get('/perfil',  [EmpleadosCtrl::class, 'perfilForm']);
    Route::post('/perfil', [EmpleadosCtrl::class, 'perfil']);

    Route::get('/tareas',                      [TareasCtrl::class, 'index']);
    Route::get('/tareas/detalle/{tarea}',      [TareasCtrl::class, 'detalle']);

    Route::get('/tareas/completar/{tarea}',    [CompletarCtrl::class, 'formulario']);
    Route::post('/tareas/completar/{tarea}',   [CompletarCtrl::class, 'completar']);

    Route::get('/tareas/adjunto/{tarea}',      [AdjuntoCtrl::class, 'descargar']);

    /* ============== PROBLEMA 3 ============== */
    // 3.1 - JS puro + DataTables
    Route::get('/clientes-js',        [Problema3Ctrl::class, 'clientesJs']);

    // 3.2 - Vue + Quasar vía CDN
    Route::get('/clientes-vue-cdn',   [Problema3Ctrl::class, 'clientesVueCdn']);

    // 3.3 - Vue + Vite + Inertia
    Route::get('/clientes-vue-vite',  [Problema3Ctrl::class, 'clientesVueVite']);
});


/* =========================================================================
 *  RUTAS SOLO ADMIN
 * ========================================================================= */
Route::middleware(['auth', 'solo.admin'])->group(function () {

    /* --- EMPLEADOS --- */
    Route::get('/empleados',                      [EmpleadosCtrl::class, 'index']);
    Route::get('/empleados/crear',                [EmpleadosCtrl::class, 'crearForm']);
    Route::post('/empleados/crear',               [EmpleadosCtrl::class, 'crear']);
    Route::get('/empleados/editar/{empleado}',    [EmpleadosCtrl::class, 'editarForm']);
    Route::post('/empleados/editar/{empleado}',   [EmpleadosCtrl::class, 'editar']);
    Route::get('/empleados/eliminar/{empleado}',  [EmpleadosCtrl::class, 'eliminarConfirm']);
    Route::post('/empleados/eliminar/{empleado}', [EmpleadosCtrl::class, 'eliminar']);

    /* --- CLIENTES --- */
    Route::get('/clientes',                     [ClientesCtrl::class, 'index']);
    Route::get('/clientes/crear',               [ClientesCtrl::class, 'crearForm']);
    Route::post('/clientes/crear',              [ClientesCtrl::class, 'crear']);
    Route::get('/clientes/editar/{cliente}',    [ClientesCtrl::class, 'editarForm']);
    Route::post('/clientes/editar/{cliente}',   [ClientesCtrl::class, 'editar']);
    Route::get('/clientes/eliminar/{cliente}',  [ClientesCtrl::class, 'eliminarConfirm']);
    Route::post('/clientes/eliminar/{cliente}', [ClientesCtrl::class, 'eliminar']);

    /* --- CUOTAS --- */
    Route::get('/cuotas',                     [CuotasCtrl::class, 'index']);
    Route::get('/cuotas/crear',               [CuotasCtrl::class, 'crearForm']);
    Route::post('/cuotas/crear',              [CuotasCtrl::class, 'crear']);
    Route::get('/cuotas/editar/{cuota}',      [CuotasCtrl::class, 'editarForm']);
    Route::post('/cuotas/editar/{cuota}',     [CuotasCtrl::class, 'editar']);
    Route::get('/cuotas/eliminar/{cuota}',    [CuotasCtrl::class, 'eliminarConfirm']);
    Route::post('/cuotas/eliminar/{cuota}',   [CuotasCtrl::class, 'eliminar']);
    Route::post('/cuotas/remesa',             [CuotasCtrl::class, 'remesaMensual']);
    Route::post('/cuotas/reenviar/{cuota}',   [CuotasCtrl::class, 'reenviarEmail']);

    Route::get('/facturas/mostrar/{cuota}',   [FacturaCtrl::class, 'mostrar']);
    Route::get('/facturas/descargar/{cuota}', [FacturaCtrl::class, 'descargar']);

    /* --- TAREAS --- */
    Route::get('/tareas/crear',                [TareasCtrl::class, 'crearForm']);
    Route::post('/tareas/crear',               [TareasCtrl::class, 'crear']);
    Route::get('/tareas/editar/{tarea}',       [TareasCtrl::class, 'editarForm']);
    Route::post('/tareas/editar/{tarea}',      [TareasCtrl::class, 'editar']);
    Route::get('/tareas/eliminar/{tarea}',     [TareasCtrl::class, 'eliminarConfirm']);
    Route::post('/tareas/eliminar/{tarea}',    [TareasCtrl::class, 'eliminar']);
});
