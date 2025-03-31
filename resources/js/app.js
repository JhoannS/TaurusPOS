import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy';

import Notifications from './Components/DeteccionInternet/Notifications.vue';

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
        const app = createApp({
            render: () => h('div', [
                h(App, props),
                h(Notifications) // 📌 Agregamos Notifications.vue globalmente
            ])
        });

        app.use(plugin).use(ZiggyVue, Ziggy);

        // 📌 Método global para mostrar notificaciones
        app.config.globalProperties.$notify = (type) => {
            window.dispatchEvent(new CustomEvent('show-notification', { detail: type }));
        };

        app.mount(el);
        return app;
    },
});

InertiaProgress.init({ color: '#4B5563' });
