import { URL, fileURLToPath } from 'node:url';
import { svelte, vitePreprocess } from '@sveltejs/vite-plugin-svelte';

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    svelte({
      preprocess: vitePreprocess(),
    }),
  ],
  resolve: {
    alias: {
      $lib: '/resources/js/Lib',
      $components: '/resources/js/Components',
      $vendor: '/vendor',
      $types: fileURLToPath(new URL('./resources/js/types', import.meta.url)),
      $layouts: fileURLToPath(
        new URL('./resources/js/Layouts', import.meta.url)
      ),
      $pages: '/resources/js/Pages',
      $lang: '/lang',
      $models: '/resources/js/models.d.ts',
    },
  },
  server: {
    hmr: {
      clientPort: 5173,
    },
  },
});
