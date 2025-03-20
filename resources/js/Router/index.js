import { createRouter, createWebHistory } from 'vue-router';

// Importar los componentes de las páginas
import Dashboard from '@/Pages/Apps/Essentials/Administrador/Dashboard/Dashboard.vue';

const routes = [
  {
    path: '/Essentials/Administrador/dashboard',
    name: 'essentials.admin.dashboard', // El nombre de la ruta
    component: Dashboard, // El componente que se debe cargar
  },
  // Aquí van otras rutas...
];

const router = createRouter({
  history: createWebHistory(),
  routes, // Definición de rutas
});

export default router;
