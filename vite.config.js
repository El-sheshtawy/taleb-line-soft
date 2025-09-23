import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/jquery.dataTables.min.css',
                'resources/css/remixicon.css',
                'resources/css/sweetalert2.min.css',
                'resources/css/bootstrap.min.css',
                'resources/css/select2.min.css',
                'resources/css/style.css',
                'resources/css/responsive_min.css',
          ],
            refresh: true,
        }),
    ],
});
