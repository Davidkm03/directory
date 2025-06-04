import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'url';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // Configuración del servidor de desarrollo de Vite
    server: {
        // Aceptar conexiones desde cualquier IP
        host: '0.0.0.0',
        // Forzar el uso de esta URL al generar los enlaces en el cliente
        // para asegurar la compatibilidad con Laravel
        origin: 'http://127.0.0.1:5173',
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/ts', import.meta.url))
        }
    }
});
