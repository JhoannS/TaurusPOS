import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3'; // Inertia.js para Vue3
import { InertiaProgress } from '@inertiajs/progress'; // Barra de progreso de Inertia

// Registrar todas las páginas con `import.meta.glob`
const pages = import.meta.glob('./Pages/**/*.vue');

// Resolver la página de acuerdo con su nombre
const resolvePageComponent = (name) => {
    const path = `./Pages/${name}.vue`;
    if (!pages[path]) {
        throw new Error(`Page not found: ${path}`);
    }
    return pages[path]().then((module) => module.default);
};

// Configurar la aplicación Inertia
createInertiaApp({
    title: (title) => `${title} - TaurusPOS`, // Título de la página
    resolve: (name) => resolvePageComponent(name), // Resolver las páginas dinámicamente
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin) // Usar el plugin de Inertia
            .mount(el); // Montar la aplicación
    },
});

// Inicializar la barra de progreso de Inertia
InertiaProgress.init({ color: '#4B5563' });
