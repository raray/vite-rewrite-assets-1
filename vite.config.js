import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    emptyOutDir: false,
    manifest: true,
    outDir: 'asset-dist',
    rollupOptions: {
      input: ['asset-src/main.js',],
    },
  },
  server: {
    strictPort: true,
    port: 5174,
  },
})
