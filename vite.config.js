import { URL, fileURLToPath } from 'node:url';
import { svelte, vitePreprocess } from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    svelte(),
    tailwindcss(),
  ],
  resolve: {
    alias: {
      $lib: fileURLToPath(new URL('./resources/js/Lib', import.meta.url)),
      $components: fileURLToPath(
        new URL('./resources/js/Components', import.meta.url)
      ),
      $vendor: fileURLToPath(new URL('./vendor', import.meta.url)),
      $types: fileURLToPath(new URL('./resources/js/types', import.meta.url)),
      $layouts: fileURLToPath(
        new URL('./resources/js/Layouts', import.meta.url)
      ),
      $pages: fileURLToPath(new URL('./resources/js/Pages', import.meta.url)),
      $lang: fileURLToPath(new URL('./lang', import.meta.url)),
      $models: fileURLToPath(
        new URL('./resources/js/models.d.ts', import.meta.url)
      ),
    },
  },
  server: {
    hmr: {
      clientPort: 5173,
    },
  },
});
