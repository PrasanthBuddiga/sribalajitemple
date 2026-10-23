// @ts-check
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  site: 'https://prasanthbuddiga.github.io/sribalajitemple',
  base: '/sribalajitemple',
  vite: {
    plugins: [tailwindcss()]
  }
});