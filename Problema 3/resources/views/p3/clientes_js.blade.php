@extends('layout.base')

@section('titulo', 'Clientes (JS + DataTables) · Problema 3.1')

@section('head-extra')
    {{-- DataTables CSS vía CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    {{-- SweetAlert2 para los modales de confirmación --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Pequeños ajustes para que DataTables encaje con Bootstrap */
        table.dataTable thead th { vertical-align: middle; }
        #tablaClientes_wrapper .dt-buttons { margin-bottom: 0.5rem; }
    </style>
@endsection

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="mb-0">
            <i class="fa-solid fa-table"></i> Clientes (JS + DataTables)
        </h2>
        <div>
            <span class="badge bg-info">Problema 3.1</span>
            <button class="btn btn-primary ms-2" id="btnNuevo">
                <i class="fa-solid fa-plus"></i> Nuevo cliente
            </button>
        </div>
    </div>

    <p class="text-muted small">
        Este CRUD usa <strong>JavaScript puro</strong> con <code>fetch()</code>,
        <strong>DataTables</strong> para la tabla y <strong>SweetAlert2</strong>
        para las confirmaciones. Toda la página se actualiza sin recargarse.
    </p>

    {{-- Tabla que rellena DataTables --}}
    <table id="tablaClientes" class="table table-striped table-bordered w-100">
        <thead class="table-dark">
            <tr>
                <th>CIF</th>
                <th>Nombre</th>
                <th>País</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Cuota</th>
                <th style="width: 130px;">Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>


    {{-- ==================================================================
         MODAL de alta/edición (Bootstrap)
         ================================================================== --}}
    <div class="modal fade" id="modalCliente" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formCliente" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span id="modalTitulo">Nuevo cliente</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        {{-- id oculto para editar --}}
                        <input type="hidden" name="id" id="f_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CIF</label>
                                <input type="text" name="cif" id="f_cif" class="form-control" required>
                                <div class="invalid-feedback" data-error-for="cif"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="f_nombre" class="form-control" required>
                                <div class="invalid-feedback" data-error-for="nombre"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" id="f_telefono" class="form-control" required>
                                <div class="invalid-feedback" data-error-for="telefono"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" name="correo" id="f_correo" class="form-control" required>
                                <div class="invalid-feedback" data-error-for="correo"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IBAN</label>
                                <input type="text" name="cuenta_corriente" id="f_iban" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">País</label>
                                <select name="pais_id" id="f_pais" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                                <div class="invalid-feedback" data-error-for="pais_id"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Moneda</label>
                                <input type="text" name="moneda" id="f_moneda" class="form-control" maxlength="3" required>
                                <div class="invalid-feedback" data-error-for="moneda"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Importe cuota mensual</label>
                                <input type="number" step="0.01" min="0"
                                       name="importe_cuota" id="f_importe" class="form-control" required>
                                <div class="invalid-feedback" data-error-for="importe_cuota"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- jQuery (requerido por DataTables) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    /* =====================================================================
       CRUD de clientes con JS puro + DataTables + fetch()  (Problema 3.1)
       ===================================================================== */

    // Token CSRF que Laravel necesita para POST/PUT/DELETE desde JS
    const CSRF = '{{ csrf_token() }}';

    // Cabeceras estándar para las peticiones JSON a la API
    const API_HEADERS = {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': CSRF,
    };

    // Instancia de DataTable (se rellena en el document.ready)
    let tablaDT = null;

    /* ---------------------------------------------------------------------
       1) CARGAR TABLA: pide los clientes a /api/clientes y los pinta
       --------------------------------------------------------------------- */
    async function cargarClientes() {
        try {
            const response = await fetch('/api/clientes', { headers: API_HEADERS });
            if (!response.ok) throw new Error('Error al cargar clientes');
            const clientes = await response.json();

            // Destruyo la tabla si ya existía, para poder volver a pintarla
            if (tablaDT) {
                tablaDT.clear().destroy();
            }

            // Preparo los datos para DataTables
            const filas = clientes.map(c => [
                c.cif,
                c.nombre,
                c.pais ? c.pais.nombre : '',
                c.telefono ?? '',
                c.correo,
                new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: c.moneda || 'EUR',
                }).format(c.importe_cuota),
                `
                    <button class="btn btn-sm btn-warning" data-accion="editar" data-id="${c.id}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" data-accion="eliminar" data-id="${c.id}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `
            ]);

            // Creo la DataTable con los datos y traducciones en español
            tablaDT = $('#tablaClientes').DataTable({
                data: filas,
                columns: [null, null, null, null, null, null, { orderable: false }],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json',
                },
                pageLength: 10,
            });

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo cargar la lista de clientes', 'error');
        }
    }

    /* ---------------------------------------------------------------------
       2) CARGAR PAÍSES en el <select> del modal
       --------------------------------------------------------------------- */
    async function cargarPaises() {
        try {
            const response = await fetch('/api/paises', { headers: API_HEADERS });
            const paises = await response.json();

            const $select = document.getElementById('f_pais');
            $select.innerHTML = '<option value="">-- Seleccionar --</option>';
            paises.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.nombre} (${p.moneda})`;
                opt.dataset.moneda = p.moneda;
                $select.appendChild(opt);
            });
        } catch (err) {
            console.error('No se pudieron cargar los países', err);
        }
    }

    /* ---------------------------------------------------------------------
       3) ABRIR MODAL NUEVO
       --------------------------------------------------------------------- */
    function abrirModalNuevo() {
        limpiarFormulario();
        document.getElementById('modalTitulo').textContent = 'Nuevo cliente';
        new bootstrap.Modal('#modalCliente').show();
    }

    /* ---------------------------------------------------------------------
       4) ABRIR MODAL EDITAR: pide el cliente a /api/clientes/{id} y rellena
       --------------------------------------------------------------------- */
    async function abrirModalEditar(id) {
        try {
            const response = await fetch(`/api/clientes/${id}`, { headers: API_HEADERS });
            const c = await response.json();

            document.getElementById('f_id').value       = c.id;
            document.getElementById('f_cif').value      = c.cif;
            document.getElementById('f_nombre').value   = c.nombre;
            document.getElementById('f_telefono').value = c.telefono ?? '';
            document.getElementById('f_correo').value   = c.correo;
            document.getElementById('f_iban').value     = c.cuenta_corriente ?? '';
            document.getElementById('f_pais').value     = c.pais_id;
            document.getElementById('f_moneda').value   = c.moneda;
            document.getElementById('f_importe').value  = c.importe_cuota;

            document.getElementById('modalTitulo').textContent = 'Editar cliente #' + c.id;
            limpiarErrores();
            new bootstrap.Modal('#modalCliente').show();

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo cargar el cliente', 'error');
        }
    }

    /* ---------------------------------------------------------------------
       5) GUARDAR (alta o edición)
       --------------------------------------------------------------------- */
    async function guardarCliente(e) {
        e.preventDefault();
        limpiarErrores();

        // Validación en CLIENTE (antes de enviar). Si falla, no envío.
        if (!validarEnCliente()) return;

        // Recojo los datos del formulario
        const id = document.getElementById('f_id').value;
        const datos = {
            cif:              document.getElementById('f_cif').value.trim(),
            nombre:           document.getElementById('f_nombre').value.trim(),
            telefono:         document.getElementById('f_telefono').value.trim(),
            correo:           document.getElementById('f_correo').value.trim(),
            cuenta_corriente: document.getElementById('f_iban').value.trim(),
            pais_id:          document.getElementById('f_pais').value,
            moneda:           document.getElementById('f_moneda').value.trim().toUpperCase(),
            importe_cuota:    document.getElementById('f_importe').value,
        };

        const url    = id ? `/api/clientes/${id}` : '/api/clientes';
        const method = id ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method,
                headers: API_HEADERS,
                body: JSON.stringify(datos),
            });

            // Validación en SERVIDOR falla → 422 con errors de Laravel
            if (response.status === 422) {
                const json = await response.json();
                mostrarErroresServidor(json.errors || {});
                return;
            }

            if (!response.ok) throw new Error('Error al guardar');

            // Todo OK: cierro modal, refresco tabla, aviso
            bootstrap.Modal.getInstance(document.getElementById('modalCliente')).hide();
            await cargarClientes();
            Swal.fire({
                icon: 'success',
                title: id ? '¡Cliente actualizado!' : '¡Cliente creado!',
                timer: 1500,
                showConfirmButton: false,
            });

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo guardar el cliente', 'error');
        }
    }

    /* ---------------------------------------------------------------------
       6) ELIMINAR (con confirmación)
       --------------------------------------------------------------------- */
    async function eliminarCliente(id) {
        const confirmar = await Swal.fire({
            title: '¿Eliminar cliente?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
        });

        if (!confirmar.isConfirmed) return;

        try {
            const response = await fetch(`/api/clientes/${id}`, {
                method: 'DELETE',
                headers: API_HEADERS,
            });

            // Si tiene tareas, la API devuelve 409
            if (response.status === 409) {
                const json = await response.json();
                Swal.fire('No se puede eliminar', json.error, 'warning');
                return;
            }

            if (!response.ok) throw new Error('Error al eliminar');

            await cargarClientes();
            Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1200, showConfirmButton: false });

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo eliminar el cliente', 'error');
        }
    }

    /* ---------------------------------------------------------------------
       HELPERS de formulario: limpiar, validar, mostrar errores
       --------------------------------------------------------------------- */
    function limpiarFormulario() {
        document.getElementById('formCliente').reset();
        document.getElementById('f_id').value = '';
        document.getElementById('f_moneda').value = 'EUR';
        limpiarErrores();
    }

    function limpiarErrores() {
        document.querySelectorAll('#formCliente .is-invalid')
                .forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#formCliente [data-error-for]')
                .forEach(el => el.textContent = '');
    }

    /**
     * Validación en CLIENTE: comprueba los campos antes de enviar al servidor.
     * Devuelve true si todo OK, false si hay errores (y los muestra).
     */
    function validarEnCliente() {
        limpiarErrores();
        let ok = true;

        const cif       = document.getElementById('f_cif').value.trim();
        const nombre    = document.getElementById('f_nombre').value.trim();
        const telefono  = document.getElementById('f_telefono').value.trim();
        const correo    = document.getElementById('f_correo').value.trim();
        const pais      = document.getElementById('f_pais').value;
        const moneda    = document.getElementById('f_moneda').value.trim();
        const importe   = document.getElementById('f_importe').value;

        if (cif.length < 3) {
            marcarError('cif', 'El CIF es obligatorio.');
            ok = false;
        }
        if (nombre === '') {
            marcarError('nombre', 'El nombre es obligatorio.');
            ok = false;
        }
        if (!/^[0-9+\s\-]{6,20}$/.test(telefono)) {
            marcarError('telefono', 'Teléfono no válido.');
            ok = false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            marcarError('correo', 'Correo no válido.');
            ok = false;
        }
        if (!pais) {
            marcarError('pais_id', 'Selecciona un país.');
            ok = false;
        }
        if (moneda.length !== 3) {
            marcarError('moneda', 'La moneda debe tener 3 letras.');
            ok = false;
        }
        if (importe === '' || parseFloat(importe) < 0) {
            marcarError('importe_cuota', 'El importe debe ser ≥ 0.');
            ok = false;
        }

        return ok;
    }

    /** Muestra los errores que devolvió el servidor (422). */
    function mostrarErroresServidor(errores) {
        for (const campo in errores) {
            marcarError(campo, errores[campo][0]);
        }
    }

    /** Pinta un error en un campo concreto del formulario. */
    function marcarError(campo, mensaje) {
        const input = document.querySelector(`#formCliente [name="${campo}"]`);
        const feedback = document.querySelector(`#formCliente [data-error-for="${campo}"]`);
        if (input) input.classList.add('is-invalid');
        if (feedback) feedback.textContent = mensaje;
    }

    /* ---------------------------------------------------------------------
       ARRANQUE
       --------------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', async () => {
        await cargarPaises();
        await cargarClientes();

        // Botón "Nuevo cliente"
        document.getElementById('btnNuevo').addEventListener('click', abrirModalNuevo);

        // Envío del formulario
        document.getElementById('formCliente').addEventListener('submit', guardarCliente);

        // Delegación de eventos para los botones editar/eliminar de la tabla
        document.getElementById('tablaClientes').addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-accion]');
            if (!btn) return;
            const id = btn.dataset.id;
            if (btn.dataset.accion === 'editar')   abrirModalEditar(id);
            if (btn.dataset.accion === 'eliminar') eliminarCliente(id);
        });

        // Al cambiar de país, autocompleto la moneda
        document.getElementById('f_pais').addEventListener('change', (e) => {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt?.dataset?.moneda) {
                document.getElementById('f_moneda').value = opt.dataset.moneda;
            }
        });
    });
    </script>
@endsection
