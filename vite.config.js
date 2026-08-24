import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/theme.scss',
                'resources/js/dashboard.js',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
