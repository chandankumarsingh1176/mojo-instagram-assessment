import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            publicDirectory: 'public',
            buildDirectory: 'build', // → outputs to public/build
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // ← Critical
        emptyOutDir: true,
    },
});
