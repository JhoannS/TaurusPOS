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


const showModal = ref(false);

const searchQuery = ref('');
</script>

<template>
    <div>

        <Head title="Inventarios" />
        <SaludoOpciones :auth="auth" />

        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />
            <main class="w-full h-[100%] px-[20px] py-[20px] bg-transparent">

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px] mt-4">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>

                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Mis inventarios</p>
                </div>

                <!-- header administrativo -->
                <div class="titulp-input-btn">
                    <h4 class="font-semibold text-[25px]">Gestiona tus productos, categorias y servicios</h4>
                    <p class="text-secundary-light text-[14px] -mt-[8px] bg-transparent">Gestiona a tus inventarios de diferentes stocks en el pordras crear, eliminar, actualizar, entre otras funciones.</p>

                </div>

                <div class="cardsUsuarios  justify-center flex items-center gap-4 my-10">
                    <div
                        class="cardUsuario rounded-lg p-5 w-[35%] text-center flex flex-col justify-between items-center">
                        <img src="https://images.unsplash.com/photo-1744646377302-1feb4ab3e9b9?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxMDZ8fHxlbnwwfHx8fHw%3D"
                            alt="Img" width="130px" height="130px" class="rounded-full my-2 h-[180px] shadowM">
                        <h4 class="text-[25px] font-bold">Gestor de productos</h4>
                        

                        <div class="movimientos-total flex justify-between gap-2 my-2 w-full">
                            <div class="movimien p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Ultimo movimiento:</p>
                                <p class="bg-transparent">Domingo 28 de junio de 2025 a las 00:00</p>
                            </div>
                            <div class="total p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Total de productos:</p>
                                <p class="bg-transparent">(Cantidad total en bd)</p>
                            </div>
                        </div>
                        <button class="" :class="[buttonFocus]">
                            <a :href="route('aplicacion.infoProductos', { aplicacion, rol })" class="flex gap-2">
                                <p>Gestionar</p>
                                <span class="material-symbols-rounded"> outbound </span>
                            </a>
                        </button>

                    </div>

                    <div
                        class="cardUsuario rounded-lg p-5 w-[35%] text-center flex flex-col justify-between items-center">
                        <img src="https://images.unsplash.com/photo-1744278955687-2a0216448268?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw5OHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Img" width="130px" class="rounded-full my-2 h-[180px] shadowM">
                        <h4 class="text-[25px] font-bold">Gestor de categorias</h4>
                        

                        <div class="movimientos-total flex justify-between gap-2 my-2 w-full">
                            <div class="movimien p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Ultimo movimiento:</p>
                                <p class="bg-transparent">Domingo 28 de junio de 2025 a las 00:00</p>
                            </div>
                            <div class="total p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Total de productos:</p>
                                <p class="bg-transparent">(Cantidad total en bd)</p>
                            </div>
                        </div>
                        <button class="" :class="[buttonFocus]">
                            <a :href="route('aplicacion.infoCategorias', { aplicacion, rol })" class="flex gap-2">
                                <p>Gestionar</p>
                                <span class="material-symbols-rounded"> outbound </span>
                            </a>
                        </button>

                    </div>

                    <div
                        class="cardUsuario rounded-lg p-5 w-[35%] text-center flex flex-col justify-between items-center">
                        <img src="https://plus.unsplash.com/premium_photo-1742945845688-7c11c2f6d33d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw1fHx8ZW58MHx8fHx8"
                            alt="Img" width="130px" class="rounded-full my-2 h-[180px] shadowM">
                        <h4 class="text-[25px] font-bold">Gestor de servicios</h4>
                        

                        <div class="movimientos-total flex justify-between gap-2 my-2 w-full">
                            <div class="movimien p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Ultimo movimiento:</p>
                                <p class="bg-transparent">Domingo 28 de junio de 2025 a las 00:00</p>
                            </div>
                            <div class="total p-2 text-[14px] rounded-lg w-[50%]">
                                <p class="bg-transparent">Total de productos:</p>
                                <p class="bg-transparent">(Cantidad total en bd)</p>
                            </div>
                        </div>
                        <button class="" :class="[buttonFocus]">
                            <a :href="route('aplicacion.infoServicios', { aplicacion, rol })" class="flex gap-2">
                                <p>Gestionar</p>
                                <span class="material-symbols-rounded"> outbound </span>
                            </a>
                        </button>

                    </div>

                </div>


            </main>


        </div>
    </div>
</template>
