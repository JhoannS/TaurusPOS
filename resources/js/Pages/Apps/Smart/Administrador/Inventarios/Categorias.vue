<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import Productos from '@/Components/TablaDatos/Essentials/Productos.vue';
import ExportarExcel from '@/Components/ExportarExcel/ExportarExcel.vue';
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

const searchQuery = ref('');
</script>

<template>
    <div>

        <Head title="Categorias" />


        <SaludoOpciones :auth="auth"/>
        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth"/>

            <main class="w-full h-[100%] px-[20px] py-[20px] bg-transparent">

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px] mt-4">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })" class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a :href="route('aplicacion.inventarios', { aplicacion, rol })" class="hover:text-essentials-secundary">
                        <p>Inventarios</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Gestion de mis categorias</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Gestion de mis categorias</h4>
                    <div class="input-buscador">
                        <input v-model="searchQuery" type="search" placeholder="Buscar categoria..." class="" />
                        <span class="material-symbols-rounded" :class="[textFocus]">travel_explore</span>
                    </div>



                    <a :href="route('aplicacion.crearCategorias', { aplicacion, rol })">
                        <button class="" :class="[buttonFocus]">Agregar nueva categoría<span
                                class="material-symbols-rounded"> outbound </span></button>
                    </a>



                </div>

                <div class="tarjetas-overviews my-7 flex w-full gap-2 justify-between">
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[12px]">Son datos y hay que darlos</p>
                            <p class="text-[14px]">Producto con menor Stock:</p>
                            <p class="font-bold text-[18px]">Producto pronto a terminarse</p>
                        </div>
                        <div
                            class="contador p-2 rounded-md flex justify-center items-center  w-10 h-10 font-bold" :class="[bgFocus]">
                            <span class="material-symbols-rounded">
                                category
                            </span>
                        </div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[12px]">Son datos y hay que darlos</p>
                            <p class="text-[14px]">Ultimo movimiento en la base de datos:</p>
                            <p class="font-bold text-[18px]">Fecha de ultimo movimiento</p>
                        </div>
                        <div
                        class="contador p-2 rounded-md flex justify-center items-center  w-10 h-10 font-bold" :class="[bgFocus]">
                            <span class="material-symbols-rounded">
                                edit_calendar
                            </span>
                        </div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[12px]">Son datos y hay que darlos</p>
                            <p class="text-[14px]">Items registrados:</p>
                            <p class="font-bold text-[18px]">total de items</p>
                        </div>
                        <div
                        class="contador p-2 rounded-md flex justify-center items-center  w-10 h-10 font-bold" :class="[bgFocus]">
                            <span class="material-symbols-rounded">
                                diversity_1
                            </span>
                        </div>
                    </div>

                </div>

                <ExportarExcel idTabla="tabla" nombreArchivo="Movimientos_Almacenados"
                    titulo="Movimientos almacenados" />
                <!-- formulario -->
                <Productos :searchQuery="searchQuery" />
            </main>


        </div>
    </div>
</template>
