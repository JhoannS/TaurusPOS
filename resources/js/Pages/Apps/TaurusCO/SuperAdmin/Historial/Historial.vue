<script setup>
import { ref, computed } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';;
import dayjs from 'dayjs'
import 'dayjs/locale/es'
import localizedFormat from 'dayjs/plugin/localizedFormat'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(localizedFormat)
dayjs.extend(relativeTime)
dayjs.locale('es')


const props = defineProps({
    auth: Object,
    auditorias: Array

});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

const formatFecha = (fecha) => {
    if (!fecha) return 'Sin fecha'

    const fechaStr = typeof fecha === 'string' ? fecha : new Date(fecha).toISOString()
    const fechaObj = dayjs(fechaStr)

    return `${fechaObj.format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')} (${fechaObj.fromNow()})`
}

// Definir los colores
const bgColor = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'Smart': 'bg-smart-primary shadow-smart text-mono-negro',
    'default': 'bg-gray-300 shadow-gray-300'
};

const button = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Essentials': 'bg-essentials-primary shadow-essentials rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Machine': 'bg-machine-primary shadow-machine rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Shopper': 'bg-shopper-primary shadow-shopper rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Smart': 'bg-smart-primary shadow-smart text-mono-negro rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'default': 'bg-gray-300 shadow-gray-300'
};

const bgText = {
    'TaurusCO': 'text-universal-naranja',
    'Essentials': 'text-essentials-primary',
    'Machine': 'text-machine-primary',
    'Shopper': 'text-shopper-primary',
    'Smart': 'text-smart-primary',
    'default': 'text-gray-300'
};
const colores2 = {
    'TaurusCO': 'bg-universal-naranja',
    'Essentials': 'bg-essentials-primary',
    'Machine': 'bg-machine-primary',
    'Shopper': 'bg-shopper-primary',
    'Smart': 'bg-smart-primary',
    'default': 'bg-gray-300 shadow-gray-300'
};



// Primero definimos appName para obtener el nombre de la app
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');

// Luego definimos hoverClass usando appName
const hoverClass = computed(() => {
    switch (appName.value) {
        case 'TaurusCO':
            return 'hover:border-universal-naranja ';
        case 'Essentials':
            return 'hover:border-essentials-primary ';
        case 'Machine':
            return 'hover:border-machine-primary ';
        case 'Shopper':
            return 'hover:border-shopper-primary ';
        case 'Smart':
            return 'hover:border-smart-primary ';
        default:
            return 'hover:border-gray-300';
    }
});





const bgFocus = computed(() => bgColor[appName.value]);
const textFocus = computed(() => bgText[appName.value]);
const buttonFocus = computed(() => button[appName.value]);
const bg = computed(() => colores2[appName.value]);

// Mapeo de acciones a iconos y colores
const iconos = {
    create: { icon: 'add_circle', color: 'bg-green-200 text-green-800' },
    Modificado: { icon: 'edit', color: 'bg-gradient-to-r from-green-200 to-green-400 text-green-900' },
    Eliminado: { icon: 'delete', color: 'bg-gradient-to-r from-red-200 to-red-400 text-red-900' },
    Iniciado_sesión: { icon: 'Login', color: 'bg-gradient-to-r from-blue-200 to-blue-400 text-blue-900' },
    Cerrado_sesión: { icon: 'Logout', color: 'bg-gradient-to-r from-orange-200 to-orange-400 text-orange-900 ' },
    navegacion: { icon: 'visibility', color: 'bg-gray-200 text-gray-800' },
    default: { icon: 'info', color: 'bg-slate-200 text-slate-800' }
}

</script>

<template>
    <div>

        <Head title="Historial" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />

            <main class="w-full h-[100%] px-[40px] bg-transparent">
                <SaludoOpciones :auth="auth" />

                <!-- navegable -->
                <div class="options flex gap-1 items-center my-3 text-[14px]">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Historial de movimientos</p>
                </div>


                <div class="my-7 flex justify-between items-center">
                    <div class="encabezado flex gap-2 items-center bg-transparent">
                        <div class=" h-[20px] w-[20px] rounded-full" :class="[bgFocus]">
                        </div>
                        <p class="bg-transparent">Historial de movimientos:</p>
                    </div>

                    <div class="flex items-center gap-[5px] my-[5px] bg-transparent">
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" value="Todos" name="CategoriasDash" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Todos</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="radio-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                    value="Creaciones" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Creaciones</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                    value="Eliminaciones" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Eliminaciones</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                    value="Actualizaciones" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Actualizaciones</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash" value="Ventas" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Ventas</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                    value="Devoluciones" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Devoluciones</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper-16">
                            <label class="checkbox-wrapper">
                                <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                    value="Inicio Sesion" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-label">Inicio de sesion</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="cardInfo my-4 flex flex-col gap-2 h-[65vh] max-h-[80vh] overflow-y-auto scrollbar-custom">
                    <transition-group name="fade" tag="div">
                    <div v-for="a in auditorias" :key="a.id"
                        class="anuncio mr-2 flex justify-between gap-3 items-center my-1 rounded-lg p-3">
                        <div class="iconoIfo flex items-center gap-3">
                            <span
                                class="material-symbols-rounded p-3 rounded-lg shadow transition-transform duration-300 hover:scale-110"
                                :class="iconos[a.accion_normalizada]?.color || iconos.default.color">
                                {{ iconos[a.accion_normalizada]?.icon || iconos.default.icon }}
                            </span>

                            <div class="info w-full">
                                <p>
                                    El <template v-if="a.modelo_id"> usuario: <strong>{{ a.modelo_id
                                            }}</strong></template>
                                    se ha <strong>{{ a.accion }}</strong>

                                </p>
                                <div class="creacion-id gap-4 flex justify-between text-[12px]">
                                    <p>Acción realizada por: <span>{{ a.usuario }}</span>.</p>
                                    <p>ID movimiento: <span>{{ a.id }}</span>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="fecha-categoria flex flex-col items-end gap-2 text-[12px]">
                            <p>{{ formatFecha(a.fecha) }}</p>
                            <p class="p-2 rounded" :class="iconos[a.accion_normalizada]?.color || iconos.default.color">
                                {{ a.comentario }}
                            </p>
                        </div>
                    </div>
                </transition-group>
                </div>
            </main>


        </div>
    </div>
</template>
