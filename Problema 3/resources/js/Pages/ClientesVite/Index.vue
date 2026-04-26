<script setup>
/**
 * ClientesVite/Index.vue
 * ----------------------
 * Página principal del apartado 3.3 (Vue + Vite + Inertia).
 *
 * - Se renderiza desde Problema3Ctrl::clientesVueVite()
 *   mediante return inertia('ClientesVite/Index').
 * - Usa axios para hablar con /api/clientes.
 * - Muestra los datos en tabla reactiva y usa el componente
 *   ClienteFormModal para crear/editar.
 *
 * @author  Alumno DWES
 */

import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import ClienteFormModal from '../../components/ClienteFormModal.vue';

/* ------------------- ESTADO ------------------- */
const clientes = ref([]);
const paises   = ref([]);
const filtro   = ref('');
const cargando = ref(false);

// Control del modal
const modalAbierto  = ref(false);
const clienteEditar = ref(null); // null = crear

/* ------------------- CARGA INICIAL ------------------- */
async function cargar() {
    cargando.value = true;
    try {
        const [r1, r2] = await Promise.all([
            window.axios.get('/api/clientes'),
            window.axios.get('/api/paises'),
        ]);
        clientes.value = r1.data;
        paises.value   = r2.data;
    } catch (e) {
        console.error(e);
        alert('Error cargando los datos');
    } finally {
        cargando.value = false;
    }
}

onMounted(cargar);

/* ------------------- BUSCADOR (computed) ------------------- */
const clientesFiltrados = computed(() => {
    if (!filtro.value) return clientes.value;
    const q = filtro.value.toLowerCase();
    return clientes.value.filter(c =>
        c.cif.toLowerCase().includes(q) ||
        c.nombre.toLowerCase().includes(q) ||
        c.correo.toLowerCase().includes(q)
    );
});

/* ------------------- ACCIONES ------------------- */
function abrirNuevo() {
    clienteEditar.value = null;
    modalAbierto.value = true;
}

function abrirEditar(c) {
    clienteEditar.value = c;
    modalAbierto.value = true;
}

function onGuardado() {
    cargar(); // refresco la lista cuando el modal emite 'guardado'
}

async function eliminar(c) {
    if (!confirm(`¿Eliminar al cliente "${c.nombre}"?`)) return;

    try {
        await window.axios.delete(`/api/clientes/${c.id}`);
        await cargar();
    } catch (err) {
        if (err.response?.status === 409) {
            alert(err.response.data.error);
        } else {
            alert('Error al eliminar el cliente.');
        }
    }
}

/* ------------------- HELPERS ------------------- */
function formatoMoneda(valor, moneda) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: moneda || 'EUR',
    }).format(valor);
}
</script>


<template>
    <Head title="Clientes (Vue + Vite)" />

    <div class="container my-4">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h4 class="mb-0">
                    <i class="fa-solid fa-bolt"></i>
                    Clientes (Vue + Vite + Inertia)
                </h4>
                <span class="badge bg-light text-primary">Problema 3.3</span>
            </div>

            <div class="card-body">
                <p class="text-muted small">
                    Este CRUD está hecho con <strong>Vue 3</strong> usando componentes
                    <code>.vue</code> compilados con <strong>Vite</strong>, e
                    <strong>Inertia.js</strong> como puente entre Laravel y la SPA.
                    Es la forma más moderna de integrar Vue con Laravel.
                </p>

                <!-- Barra de acciones -->
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-primary" @click="abrirNuevo">
                        <i class="fa-solid fa-plus"></i> Nuevo cliente
                    </button>
                    <input v-model="filtro" class="form-control"
                           placeholder="Buscar por CIF, nombre o correo...">
                </div>

                <!-- Tabla -->
                <div v-if="cargando" class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                </div>
                <div v-else-if="clientesFiltrados.length === 0" class="alert alert-info">
                    No hay clientes que coincidan.
                </div>
                <table v-else class="table table-striped table-bordered align-middle">
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
                    <tbody>
                        <tr v-for="c in clientesFiltrados" :key="c.id">
                            <td>{{ c.cif }}</td>
                            <td>{{ c.nombre }}</td>
                            <td>{{ c.pais?.nombre }}</td>
                            <td>{{ c.telefono }}</td>
                            <td>{{ c.correo }}</td>
                            <td>{{ formatoMoneda(c.importe_cuota, c.moneda) }}</td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning" @click="abrirEditar(c)">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="eliminar(c)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-muted small">
                Total: {{ clientes.length }} clientes · Mostrando: {{ clientesFiltrados.length }}
            </div>
        </div>

        <!-- Modal reutilizable -->
        <ClienteFormModal
            v-model="modalAbierto"
            :cliente="clienteEditar"
            :paises="paises"
            @guardado="onGuardado"
        />
    </div>
</template>
