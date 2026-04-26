<script setup>
/**
 * ClienteFormModal.vue
 * --------------------
 * Modal reutilizable para crear / editar un cliente.
 *
 * Props:
 *   - modelValue  (Boolean): v-model para mostrar/ocultar el modal
 *   - cliente     (Object|null): si es objeto → editar; si null → crear
 *   - paises      (Array): lista de países para el <select>
 *
 * Emits:
 *   - update:modelValue  (para v-model)
 *   - guardado           (cuando se guarda correctamente; el padre refresca)
 *
 * @author  Alumno DWES
 */

import { ref, watch, reactive } from 'vue';

const props = defineProps({
    modelValue: Boolean,
    cliente:    { type: Object, default: null },
    paises:     { type: Array,  default: () => [] },
});

const emit = defineEmits(['update:modelValue', 'guardado']);

// Estructura del formulario
function formularioVacio() {
    return {
        id: null,
        cif: '',
        nombre: '',
        telefono: '',
        correo: '',
        cuenta_corriente: '',
        pais_id: '',
        moneda: 'EUR',
        importe_cuota: 0,
    };
}

const form     = reactive(formularioVacio());
const errores  = ref({});
const enviando = ref(false);

// Cada vez que cambie la prop `cliente`, relleno el formulario
watch(() => props.cliente, (nuevo) => {
    errores.value = {};
    if (nuevo) {
        Object.assign(form, {
            id:               nuevo.id,
            cif:              nuevo.cif,
            nombre:           nuevo.nombre,
            telefono:         nuevo.telefono ?? '',
            correo:           nuevo.correo,
            cuenta_corriente: nuevo.cuenta_corriente ?? '',
            pais_id:          nuevo.pais_id,
            moneda:           nuevo.moneda,
            importe_cuota:    nuevo.importe_cuota,
        });
    } else {
        Object.assign(form, formularioVacio());
    }
}, { immediate: true });

// Cerrar
function cerrar() {
    emit('update:modelValue', false);
}

// Al cambiar de país, autocompleto la moneda
function onCambioPais() {
    const pais = props.paises.find(p => p.id === Number(form.pais_id));
    if (pais) form.moneda = pais.moneda;
}

// Validación ligera en cliente
function validarEnCliente() {
    const e = {};
    if (!form.cif || form.cif.length < 3)   e.cif = ['CIF obligatorio'];
    if (!form.nombre)                       e.nombre = ['Nombre obligatorio'];
    if (!/^[0-9+\s\-]{6,20}$/.test(form.telefono)) e.telefono = ['Teléfono no válido'];
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.correo)) e.correo = ['Correo no válido'];
    if (!form.pais_id)                      e.pais_id = ['Selecciona país'];
    if (!form.moneda || form.moneda.length !== 3) e.moneda = ['3 letras'];
    if (form.importe_cuota === '' || parseFloat(form.importe_cuota) < 0)
        e.importe_cuota = ['Importe ≥ 0'];

    errores.value = e;
    return Object.keys(e).length === 0;
}

// Guardar (POST o PUT)
async function guardar() {
    if (!validarEnCliente()) return;

    enviando.value = true;
    try {
        const url    = form.id ? `/api/clientes/${form.id}` : '/api/clientes';
        const metodo = form.id ? 'put' : 'post';

        const { data } = await window.axios[metodo](url, form);
        emit('guardado', data);
        cerrar();
    } catch (err) {
        // 422 → errores de validación del servidor
        if (err.response?.status === 422) {
            errores.value = err.response.data.errors ?? {};
        } else {
            alert('Error al guardar el cliente.');
        }
    } finally {
        enviando.value = false;
    }
}
</script>


<template>
    <!--
      Modal con CSS puro (para no depender de Bootstrap JS desde Vue).
      Se muestra/oculta con un simple v-if.
    -->
    <div v-if="modelValue" class="modal-overlay" @click.self="cerrar">
        <div class="modal-card">

            <div class="modal-header">
                <h5>{{ form.id ? `Editar cliente #${form.id}` : 'Nuevo cliente' }}</h5>
                <button class="btn-close" @click="cerrar" aria-label="Cerrar">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">CIF</label>
                        <input v-model="form.cif" class="form-control"
                               :class="{ 'is-invalid': errores.cif }">
                        <div v-if="errores.cif" class="invalid-feedback">{{ errores.cif[0] }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input v-model="form.nombre" class="form-control"
                               :class="{ 'is-invalid': errores.nombre }">
                        <div v-if="errores.nombre" class="invalid-feedback">{{ errores.nombre[0] }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input v-model="form.telefono" class="form-control"
                               :class="{ 'is-invalid': errores.telefono }">
                        <div v-if="errores.telefono" class="invalid-feedback">{{ errores.telefono[0] }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input v-model="form.correo" type="email" class="form-control"
                               :class="{ 'is-invalid': errores.correo }">
                        <div v-if="errores.correo" class="invalid-feedback">{{ errores.correo[0] }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">IBAN</label>
                        <input v-model="form.cuenta_corriente" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">País</label>
                        <select v-model="form.pais_id" class="form-select"
                                @change="onCambioPais"
                                :class="{ 'is-invalid': errores.pais_id }">
                            <option value="">-- Seleccionar --</option>
                            <option v-for="p in paises" :key="p.id" :value="p.id">
                                {{ p.nombre }} ({{ p.moneda }})
                            </option>
                        </select>
                        <div v-if="errores.pais_id" class="invalid-feedback">{{ errores.pais_id[0] }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Moneda</label>
                        <input v-model="form.moneda" maxlength="3" class="form-control"
                               :class="{ 'is-invalid': errores.moneda }">
                        <div v-if="errores.moneda" class="invalid-feedback">{{ errores.moneda[0] }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Importe</label>
                        <input v-model="form.importe_cuota" type="number" step="0.01"
                               class="form-control"
                               :class="{ 'is-invalid': errores.importe_cuota }">
                        <div v-if="errores.importe_cuota" class="invalid-feedback">{{ errores.importe_cuota[0] }}</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" @click="cerrar">Cancelar</button>
                <button class="btn btn-primary" :disabled="enviando" @click="guardar">
                    <i class="fa-solid fa-save"></i>
                    {{ enviando ? 'Guardando...' : 'Guardar' }}
                </button>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Modal simple sin depender del JS de Bootstrap */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 4vh;
    z-index: 1050;
}
.modal-card {
    background: #fff;
    border-radius: 0.8rem;
    width: 95%;
    max-width: 780px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}
.modal-header {
    background: linear-gradient(90deg, #0d47a1, #1976d2);
    color: #fff;
    padding: 0.8rem 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h5 { margin: 0; font-size: 1.1rem; }
.btn-close {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
}
.modal-body { padding: 1.2rem; max-height: 70vh; overflow-y: auto; }
.modal-footer {
    padding: 0.8rem 1.2rem;
    background: #f6f8fb;
    border-top: 1px solid #e4e9f0;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}
</style>
