<script setup>
import { Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, computed } from 'vue';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import BarraCalendario from '@/Components/BarraCalendario/BarraCalendario.vue';
import DetallesPlan from '@/Components/Dashboard/DetallesPlan.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';

const props = defineProps({
    auth: {
        type: Object,
        required: true
    },
    detalles: {
        type: Object,
        required: true
    },
});

const saludo = ref('');

const actualizarSaludo = () => {
    const hora = new Date().getHours();
    if (hora < 12) saludo.value = '¡Buenos días!';
    else if (hora < 18) saludo.value = '¡Buenas tardes!';
    else saludo.value = '¡Buenas noches!';
};

onMounted(() => {
    actualizarSaludo();
});

// Definir los colores
const colores = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
    'default': 'bg-gray-300 shadow-gray-300'
};
const colores2 = {
    'TaurusCO': 'bg-universal-naranja',
    'Essentials': 'bg-essentials-primary rounded-full z-10',
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
            return 'hover:bg-universal-naranja';
        case 'Essentials':
            return 'hover:bg-essentials-primary';
        case 'Machine':
            return 'hover:bg-machine-primary';
        case 'Shopper':
            return 'hover:bg-shopper-primary';
        case 'Smart':
            return 'hover:bg-smart-primary hover:text-mono-negro';
        default:
            return 'hover:bg-gray-300';
    }
});

const bgFocus = computed(() => colores[appName.value]);
const bg = computed(() => colores2[appName.value]);
</script>

<template>
    <div>

        <Head title="Dashboard Essentials" />
        <SaludoOpciones :auth="auth" />
        
        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />
            <main class="w-full h-[100%] px-[40px] bg-transparent">
                <div class="flex gap-[10px] my-6">
                    <div class="left w-[35%] h-[85vh] flex flex-col gap-5 justify-between px-3">
                        <div class="saludo-btn flex flex-col justify-center items-center gap-3">
                            <h4 class="text-[25px] text-center">
                                <span id="saludo">{{ saludo }}</span> {{ auth.user.nombres_ct }}, espero te encuentres
                                muy bien, ¿Listo para vender?
                            </h4>
                            <p>La aplicación más profesional del mercado.</p>
                            <button
                                class="flex items-center cursor-pointer justify-center gap-3 py-[10px] px-[40px] rounded-xl hover:shadow-lg"
                                :class="[bgFocus]">
                                Vender con POS
                                <span class="material-symbols-rounded"> outbound </span>
                            </button>
                        </div>

                        <div class="chart bg-transparent">
                            <div class="pastel h-[150px] w-full flex justify-center items-center my-[10px]">
                                <div class="circulo rounded-full bg-orange-400 h-full w-[150px]"></div>
                            </div>

                            <div class="info flex items-center justify-between gap-5">
                                <div class="realizados">
                                    <p class="text-semaforo-verde">Servicios realizados:</p>
                                    <p class="number text-[20px]">Numero</p>
                                </div>
                                <div class="balance">
                                    <p class="text-semaforo-verde">Balance general:</p>
                                    <p class="number text-[20px]">$ cantidad vendida</p>
                                </div>
                            </div>

                            <div class="tags flex justify-between my-2">
                                <div class="curso flex flex-col">
                                    <p class="text-[12px]">En curso:</p>
                                    <div class="tag bg-semaforo-verde rounded-lg w-full h-[4px]"></div>
                                    <span class="text-[12px]">#<span class="text-[12px]"> Servicios</span></span>
                                </div>
                                <div class="proxima flex flex-col">
                                    <p class="text-[12px]">Proximas:</p>
                                    <div class="tag bg-semaforo-amarillo rounded-lg w-full h-[4px]"></div>
                                    <span class="text-[12px]">#<span class="text-[12px]"> Servicios</span></span>
                                </div>
                                <div class="cancelada flex flex-col">
                                    <p class="text-[12px]">Canceladas:</p>
                                    <div class="tag bg-semaforo-rojo rounded-lg w-full h-[4px]"></div>
                                    <span class="text-[12px]">#<span class="text-[12px]"> Servicios</span></span>
                                </div>
                            </div>
                        </div>
                        <BarraCalendario :auth="auth" />
                    </div>

                    <div class="right w-[65%] px-3 flex flex-col ">
                        
                       
                        <DetallesPlan :auth="auth" :detalles="detalles" />

                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
