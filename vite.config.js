import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';




export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: process.env.VITE_HOST || '127.0.0.1',
        port: 5173,
        strictPort: true,
        origin: process.env.VITE_DEV_SERVER_URL || undefined,
        hmr: {
            host: process.env.VITE_HOST || undefined,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
