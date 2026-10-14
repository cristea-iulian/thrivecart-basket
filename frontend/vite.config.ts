import { defineConfig } from 'vite';
export default defineConfig({ server: { proxy: { '/api': { target: 'http://127.0.0.1:8081', rewrite: path => path.replace(/^\/api/, '') || '/' } } } });
