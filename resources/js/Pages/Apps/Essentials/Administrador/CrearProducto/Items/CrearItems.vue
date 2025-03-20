<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import CrearItem from '@/Components/CrearProductos/Essentials/Items/CrearItem.vue';



export default {
    name: 'InfoSucursales',
    components: {
        Head,
    },
}
</script>

<script setup>

import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js'; // Importación nombrada


//
// Reloj en tiempo real
//
const dia = ref('');
const mes = ref('');
const anio = ref('');
const hora = ref('');
const saludo = ref('');

function actualizarFechaHora() {
    const fecha = new Date();
    dia.value = fecha.getDate();

    const monthNamesClock = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    mes.value = monthNamesClock[fecha.getMonth()];
    anio.value = fecha.getFullYear();

    let horas = fecha.getHours();
    const minutos = fecha.getMinutes().toString().padStart(2, "0");
    const segundos = fecha.getSeconds().toString().padStart(2, "0");
    const periodo = horas >= 12 ? "pm" : "am";

    if (horas > 12) {
        horas -= 12;
    } else if (horas === 0) {
        horas = 12;
    }
    hora.value = `${horas}:${minutos}:${segundos} ${periodo}`;

    if (fecha.getHours() < 12) {
        saludo.value = "¡Buenos días";
    } else if (fecha.getHours() < 18) {
        saludo.value = "¡Buenas tardes";
    } else {
        saludo.value = "¡Buenas noches";
    }
}

let clockInterval = null;
onMounted(() => {
    actualizarFechaHora();
    clockInterval = setInterval(actualizarFechaHora, 1000);
});
onUnmounted(() => {
    clearInterval(clockInterval);
});

const page = usePage();
const currentRoute = computed(() => page.props.value.currentRoute);

const searchQuery = ref('');
</script>

<template>
    <div>

        <Head title="Info de mi sucursal" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar />

            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones />

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px]">
                    <a :href="route('essentials.admin.dashboard')" class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a :href="route('essentials.admin.multisucursales')" class="hover:text-essentials-secundary">
                        <p>Sucursales activas</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a :href="route('essentials.admin.infoSucursal')" class="hover:text-essentials-secundary">
                        <p>Gestionar mi sucursal</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Agregar nuevo item</p>
                </div>

                

                <CrearItem />
                
            </main>


        </div>
    </div>
</template>
