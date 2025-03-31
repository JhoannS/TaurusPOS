import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

import { ZiggyVue } from 'ziggy-js'; // ✅ Importación desde ziggy-js
import { Ziggy } from './ziggy';

// Registrar todas las páginas con `import.meta.glob`
const pages = import.meta.glob('./Pages/**/*.vue');

const resolvePageComponent = (name) => {
    const path = `./Pages/${name}.vue`;
    if (!pages[path]) {
        throw new Error(`Page not found: ${path}`);
    }
    return pages[path]().then((module) => module.default);
};

createInertiaApp({
    title: (title) => `${title} | Taurus CO`,
    resolve: (name) => resolvePageComponent(name),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin) // ✅ Usar el plugin de Inertia
            .use(ZiggyVue, Ziggy) // ✅ Registrar Ziggy como plugin correctamente
            .mount(el);

        return app;
    },
});

InertiaProgress.init({ color: '#4B5563' });
