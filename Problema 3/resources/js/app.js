/**
 * Punto de entrada de la SPA Vue + Inertia (Problema 3.3).
 *
 * Vite compila este fichero y genera un bundle que se carga
 * desde resources/views/app.blade.php mediante @vite(...).
 */

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import axios from 'axios';

// Configuro axios globalmente para que mande el token CSRF
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content;
axios.defaults.headers.common['Accept']       = 'application/json';

// Lo hago global para no importarlo en cada componente
window.axios = axios;

createInertiaApp({
    title: (title) => title ? `${title} · Nosecaen S.L.` : 'Nosecaen S.L.',

    /**
     * Resuelve qué componente .vue hay que renderizar según el name
     * que le pasa el servidor. Ej: inertia('ClientesVite/Index')
     *    → carga resources/js/Pages/ClientesVite/Index.vue
     */
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link) // componente <Link> para navegación SPA
            .mount(el);
    },

    progress: {
        color: '#0d47a1', // barra de progreso en top durante navegación
    },
});
