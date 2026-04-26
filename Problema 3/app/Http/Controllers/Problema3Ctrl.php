<?php

/**
 * Controlador para las 3 páginas del Problema 3.
 *
 * Cada método devuelve una vista Blade distinta con la misma
 * funcionalidad (CRUD de clientes) implementada con un stack
 * diferente:
 *   - /clientes-js       → JS puro + DataTables (3.1)
 *   - /clientes-vue-cdn  → Vue 3 + Quasar CDN (3.2)
 *   - /clientes-vue-vite → Vue + Vite + Inertia (3.3)
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

class Problema3Ctrl extends Controller
{
    /** 3.1 - JS puro + DataTables. */
    public function clientesJs()
    {
        return view('p3.clientes_js');
    }

    /** 3.2 - Vue + Quasar vía CDN. */
    public function clientesVueCdn()
    {
        return view('p3.clientes_vue_cdn');
    }

    /** 3.3 - Vue + Vite + Inertia. */
    public function clientesVueVite()
    {
        return inertia('ClientesVite/Index');
    }
}
