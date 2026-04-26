@extends('layout.base')

@section('titulo', 'Clientes (Vue + Quasar CDN) · Problema 3.2')

@section('head-extra')
    {{-- Material Icons + Roboto (Quasar los usa) --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    {{-- Quasar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/quasar@2.14.2/dist/quasar.prod.css" rel="stylesheet">

    <style>
        /* El layout base ya aporta fondo; Quasar pinta sus propios componentes. */
        #appVue { background: transparent; }
    </style>
@endsection

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">
            <i class="fa-solid fa-cube"></i> Clientes (Vue + Quasar CDN)
        </h2>
        <span class="badge bg-info">Problema 3.2</span>
    </div>

    <p class="text-muted small">
        Este CRUD usa <strong>Vue 3</strong> y <strong>Quasar</strong> cargados
        por CDN (sin compilar). Las peticiones a la API se hacen con <strong>axios</strong>.
        Todo es reactivo: al cambiar un dato, Vue actualiza la tabla sola.
    </p>

    {{--
      El <div id="appVue"> es donde Vue va a "montar" la aplicación.
      Dentro uso componentes de Quasar (que empiezan por q-*).
    --}}
    <div id="appVue">

        {{-- Botón nuevo + buscador --}}
        <div class="row q-mb-md q-gutter-sm">
            <q-btn color="primary" icon="add" label="Nuevo cliente"
                   @@click="abrirNuevo"></q-btn>
            <q-input outlined dense v-model="filtro"
                     label="Buscar..." class="col"></q-input>
        </div>

        {{-- Tabla Quasar reactiva --}}
        <q-table
            :rows="clientesFiltrados"
            :columns="columnas"
            row-key="id"
            :loading="cargando"
            :pagination="{ rowsPerPage: 10 }"
            flat bordered
        >
            <template v-slot:body-cell-importe_cuota="props">
                <q-td :props="props">
                    @{{ formatoMoneda(props.row.importe_cuota, props.row.moneda) }}
                </q-td>
            </template>

            <template v-slot:body-cell-pais="props">
                <q-td :props="props">
                    @{{ props.row.pais ? props.row.pais.nombre : '' }}
                </q-td>
            </template>

            <template v-slot:body-cell-acciones="props">
                <q-td :props="props">
                    <q-btn dense flat round color="warning" icon="edit"
                           @@click="abrirEditar(props.row)"></q-btn>
                    <q-btn dense flat round color="negative" icon="delete"
                           @@click="confirmarBorrado(props.row)"></q-btn>
                </q-td>
            </template>
        </q-table>


        {{-- DIALOG de alta/edición --}}
        <q-dialog v-model="dialogoAbierto" persistent>
            <q-card style="min-width: 500px; max-width: 700px;">
                <q-card-section class="bg-primary text-white">
                    <div class="text-h6">
                        @{{ editando ? 'Editar cliente #' + formulario.id : 'Nuevo cliente' }}
                    </div>
                </q-card-section>

                <q-card-section>
                    <div class="row q-col-gutter-sm">
                        <q-input class="col-6" outlined dense label="CIF"
                                 v-model="formulario.cif"
                                 :error="!!errores.cif" :error-message="errores.cif?.[0]"></q-input>
                        <q-input class="col-6" outlined dense label="Nombre"
                                 v-model="formulario.nombre"
                                 :error="!!errores.nombre" :error-message="errores.nombre?.[0]"></q-input>
                        <q-input class="col-6" outlined dense label="Teléfono"
                                 v-model="formulario.telefono"
                                 :error="!!errores.telefono" :error-message="errores.telefono?.[0]"></q-input>
                        <q-input class="col-6" outlined dense label="Correo" type="email"
                                 v-model="formulario.correo"
                                 :error="!!errores.correo" :error-message="errores.correo?.[0]"></q-input>
                        <q-input class="col-12" outlined dense label="IBAN"
                                 v-model="formulario.cuenta_corriente"></q-input>
                        <q-select class="col-6" outlined dense label="País"
                                  v-model="formulario.pais_id"
                                  :options="paisesSelect"
                                  emit-value map-options
                                  @@update:model-value="onCambioPais"
                                  :error="!!errores.pais_id" :error-message="errores.pais_id?.[0]"></q-select>
                        <q-input class="col-3" outlined dense label="Moneda"
                                 maxlength="3"
                                 v-model="formulario.moneda"
                                 :error="!!errores.moneda" :error-message="errores.moneda?.[0]"></q-input>
                        <q-input class="col-3" outlined dense label="Importe"
                                 type="number" step="0.01"
                                 v-model="formulario.importe_cuota"
                                 :error="!!errores.importe_cuota" :error-message="errores.importe_cuota?.[0]"></q-input>
                    </div>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancelar" @@click="dialogoAbierto = false"></q-btn>
                    <q-btn color="primary" label="Guardar" @@click="guardar"></q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
@endsection


@section('scripts')
    {{-- Vue 3 (production) --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

    {{-- Quasar --}}
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.14.2/dist/quasar.umd.prod.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>

    <script>
    /* =====================================================================
       CRUD clientes con Vue 3 + Quasar + Axios  (Problema 3.2)
       ===================================================================== */

    const { createApp, ref, computed, onMounted } = Vue;

    // Configuro axios para que mande el token CSRF en cada petición
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
    axios.defaults.headers.common['Accept']       = 'application/json';

    const app = createApp({
        setup() {
            /* ------------------- ESTADO (reactivo) ------------------- */
            const clientes  = ref([]);        // array de clientes
            const paises    = ref([]);        // array de países
            const cargando  = ref(false);
            const filtro    = ref('');        // buscador
            const dialogoAbierto = ref(false);
            const editando  = ref(false);     // ¿estamos editando o creando?
            const errores   = ref({});        // errores del servidor

            // El objeto formulario para el modal
            const formulario = ref(formularioVacio());

            function formularioVacio() {
                return {
                    id: null,
                    cif: '',
                    nombre: '',
                    telefono: '',
                    correo: '',
                    cuenta_corriente: '',
                    pais_id: null,
                    moneda: 'EUR',
                    importe_cuota: 0,
                };
            }

            /* ------------------- COLUMNAS DE LA TABLA ------------------- */
            const columnas = [
                { name: 'cif',           label: 'CIF',     field: 'cif',    align: 'left', sortable: true },
                { name: 'nombre',        label: 'Nombre',  field: 'nombre', align: 'left', sortable: true },
                { name: 'pais',          label: 'País',    field: 'pais',   align: 'left' },
                { name: 'telefono',      label: 'Teléfono',field: 'telefono', align: 'left' },
                { name: 'correo',        label: 'Correo',  field: 'correo', align: 'left' },
                { name: 'importe_cuota', label: 'Cuota',   field: 'importe_cuota', align: 'right', sortable: true },
                { name: 'acciones',      label: 'Acciones',field: 'id', align: 'center' },
            ];

            /* ------------------- CARGA INICIAL ------------------- */
            async function cargar() {
                cargando.value = true;
                try {
                    const [res1, res2] = await Promise.all([
                        axios.get('/api/clientes'),
                        axios.get('/api/paises'),
                    ]);
                    clientes.value = res1.data;
                    paises.value   = res2.data;
                } catch (e) {
                    Quasar.Notify.create({
                        message: 'Error al cargar datos', color: 'negative', icon: 'error'
                    });
                } finally {
                    cargando.value = false;
                }
            }

            /* ------------------- COMPUTED ------------------- */

            // Lista filtrada por el buscador
            const clientesFiltrados = computed(() => {
                if (!filtro.value) return clientes.value;
                const q = filtro.value.toLowerCase();
                return clientes.value.filter(c =>
                    c.cif.toLowerCase().includes(q) ||
                    c.nombre.toLowerCase().includes(q) ||
                    c.correo.toLowerCase().includes(q)
                );
            });

            // Opciones para el <q-select> de países
            const paisesSelect = computed(() =>
                paises.value.map(p => ({
                    label: `${p.nombre} (${p.moneda})`,
                    value: p.id,
                    moneda: p.moneda,
                }))
            );

            /* ------------------- ACCIONES ------------------- */

            function abrirNuevo() {
                formulario.value = formularioVacio();
                editando.value = false;
                errores.value = {};
                dialogoAbierto.value = true;
            }

            function abrirEditar(cliente) {
                formulario.value = {
                    id:               cliente.id,
                    cif:              cliente.cif,
                    nombre:           cliente.nombre,
                    telefono:         cliente.telefono ?? '',
                    correo:           cliente.correo,
                    cuenta_corriente: cliente.cuenta_corriente ?? '',
                    pais_id:          cliente.pais_id,
                    moneda:           cliente.moneda,
                    importe_cuota:    cliente.importe_cuota,
                };
                editando.value = true;
                errores.value = {};
                dialogoAbierto.value = true;
            }

            // Al cambiar país, autocompleto la moneda (usando el "moneda" del option)
            function onCambioPais(paisId) {
                const pais = paises.value.find(p => p.id === paisId);
                if (pais) formulario.value.moneda = pais.moneda;
            }

            // Validación ligera en cliente antes de mandar al servidor
            function validarEnCliente() {
                const e = {};
                if (!formulario.value.cif || formulario.value.cif.length < 3)
                    e.cif = ['CIF obligatorio'];
                if (!formulario.value.nombre)
                    e.nombre = ['Nombre obligatorio'];
                if (!/^[0-9+\s\-]{6,20}$/.test(formulario.value.telefono))
                    e.telefono = ['Teléfono no válido'];
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formulario.value.correo))
                    e.correo = ['Correo no válido'];
                if (!formulario.value.pais_id)
                    e.pais_id = ['Selecciona país'];
                if (!formulario.value.moneda || formulario.value.moneda.length !== 3)
                    e.moneda = ['3 letras'];
                if (formulario.value.importe_cuota === '' || parseFloat(formulario.value.importe_cuota) < 0)
                    e.importe_cuota = ['Importe ≥ 0'];

                errores.value = e;
                return Object.keys(e).length === 0;
            }

            async function guardar() {
                if (!validarEnCliente()) return;

                try {
                    const body = { ...formulario.value };
                    const url  = editando.value
                        ? `/api/clientes/${body.id}`
                        : '/api/clientes';
                    const metodo = editando.value ? 'put' : 'post';

                    await axios[metodo](url, body);
                    dialogoAbierto.value = false;
                    await cargar();

                    Quasar.Notify.create({
                        message: editando.value ? 'Cliente actualizado' : 'Cliente creado',
                        color: 'positive', icon: 'check_circle',
                        position: 'top', timeout: 1800,
                    });

                } catch (err) {
                    // Si el servidor devolvió 422, pinto sus errores
                    if (err.response?.status === 422) {
                        errores.value = err.response.data.errors ?? {};
                    } else {
                        Quasar.Notify.create({
                            message: 'Error al guardar', color: 'negative', icon: 'error',
                        });
                    }
                }
            }

            async function confirmarBorrado(cliente) {
                Quasar.Dialog.create({
                    title: 'Confirmar',
                    message: `¿Eliminar cliente "${cliente.nombre}"?`,
                    cancel: true,
                    persistent: true,
                    ok: { label: 'Eliminar', color: 'negative' },
                }).onOk(async () => {
                    try {
                        await axios.delete(`/api/clientes/${cliente.id}`);
                        await cargar();
                        Quasar.Notify.create({
                            message: 'Cliente eliminado', color: 'positive', position: 'top', timeout: 1500,
                        });
                    } catch (err) {
                        // 409 = cliente con tareas asociadas
                        const msg = err.response?.status === 409
                            ? err.response.data.error
                            : 'Error al eliminar';
                        Quasar.Notify.create({
                            message: msg, color: 'warning', icon: 'warning',
                            position: 'top', timeout: 3000,
                        });
                    }
                });
            }

            /* ------------------- HELPERS ------------------- */
            function formatoMoneda(valor, moneda) {
                return new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: moneda || 'EUR',
                }).format(valor);
            }

            /* ------------------- HOOK: al montar, cargo datos ------------------- */
            onMounted(cargar);

            /* ------------------- EXPONGO AL TEMPLATE ------------------- */
            return {
                clientes, paises, cargando, filtro,
                dialogoAbierto, editando, formulario, errores,
                clientesFiltrados, paisesSelect, columnas,
                abrirNuevo, abrirEditar, guardar, confirmarBorrado,
                onCambioPais, formatoMoneda,
            };
        }
    });

    // Le conecto Quasar y lo monto
    app.use(Quasar, {
        plugins: { Notify: Quasar.Notify, Dialog: Quasar.Dialog },
    });
    app.mount('#appVue');
    </script>
@endsection
