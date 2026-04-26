import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

/**
 * Configuración de Vite para el Problema 3.3.
 *
 * Vite es el bundler que compila los ficheros .vue y los scripts JS
 * modernos a un bundle que el navegador entiende. Lo uso SOLO para
 * el apartado 3.3 (los 3.1 y 3.2 no lo necesitan, van por CDN).
 */
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true, // recarga cuando cambia un Blade
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // Resolver rutas absolutas que empiecen por '/'
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
