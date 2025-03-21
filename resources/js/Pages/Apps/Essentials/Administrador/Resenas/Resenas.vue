<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';
import GeneradorQr from '@/Components/Qrs/GeneradorQr.vue'
import JsBarcode from 'jsbarcode'


export default {
    name: 'GeneradorQrs',
    components: {
        Head,
    },
}
</script>

<script setup>

import { ref, onMounted, onUnmounted, computed, defineEmits } from 'vue';
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

const showModal = ref(false)


</script>

<template>
    <div>

        <Head title="Gestión de Qrs" />


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
                    <p class="font-bold">Gestión de mis Qr's</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Gestión de Qr's</h4>
                    <div class="input-buscador">
                        <input v-model="searchQuery" type="search" placeholder="Buscar producto..." class="" />
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>

                    <!-- Botón para abrir el modal -->
                    <button @click="showModal = true" class="btn-essentials">
                        Crear nuevo Qr
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>
                </div>


                <GeneradorQr />



            </main>



            <Modal :isOpen="showModal" titulo="Creación de un nuevo Qr"
                descripcion="En este apartado podrás crear etiquetas facilmente con solo llenar unos campos, podras imprimirla y hasta incluso escanearla directamente"
                confirmText="Confirmar creación" @close="showModal = false" @confirm="emitirEtiqueta">
                <form @submit.prevent="emitirEtiqueta">
                    <div class="mt-[5px] flex justify-between gap-3 w-full">

                        <div class="w-[100%]">
                            <p class="my-[5px] text-[14px]">Nombre Qr:</p>
                            <div
                                class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                                <span
                                    class="material-symbols-rounded text-essentials-primary text-[20px] pl-[5px]">format_italic</span>

                                <input type="text" id="titulo"
                                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                                    placeholder="" name="tituloQr" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-[5px] flex justify-between gap-3 w-full">
                        <div class="w-[100%]">
                            <p class="my-[5px] text-[14px]">Enlace:</p>
                            <div
                                class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                                <span
                                    class="material-symbols-rounded text-essentials-primary text-[20px] pl-[5px]">link</span>

                                <input type="text" id="enlace"
                                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                                    placeholder="palabra clave Ej: Escanear" name="enlaceQr" />
                            </div>
                        </div>
                    </div>
                </form>
            </Modal>
        </div>
    </div>
</template>
