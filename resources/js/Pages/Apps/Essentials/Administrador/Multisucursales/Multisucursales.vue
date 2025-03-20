<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';



export default {
    name: 'Multisucursales',
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

const showModal = ref(false);
const branchTitle = ref('');
const branchDescription = ref('');

const createBranch = () => {
    if (branchTitle.value && branchDescription.value) {
        console.log('Sucursal creada:', {
            title: branchTitle.value,
            description: branchDescription.value,
        });
        showModal.value = false; // Cierra el modal después de crear
    } else {
        alert('Por favor completa todos los campos.');
    }
};
</script>

<template>
    <div>

        <Head title="Mis bodegas Essentials" />


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
                    <p class="font-bold">Sucursales activas</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Multisucursales</h4>
                    <div class="input-buscador">
                        <input type="search" placeholder="Buscar contenido...">
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>

                    <!-- Botón para abrir el modal -->
                    <button @click="showModal = true" class="btn-essentials">
                        Crear nueva sucursal
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>
                </div>

                <div class="tarjetas-sucursales my-7">
                    <div class="card-sucursal flex flex-col gap-2  max-w-[25%] rounded-lg p-3">
                        <img src="../Assets/img/Recursos Essentials/img-sucursal.jpg" alt="img de ref local"
                            class="rounded-lg">
                        <div class="icono-id-nombre flex gap-2 ">
                            <span
                                class="material-symbols-rounded flex justify-center items-center h-10 w-10 text-[14px] bg-essentials-primary rounded-lg">
                                swap_horiz </span>
                            <div class="idd-nombre text-[14px]">
                                <p>(Ttiulo de la sucursal)</p>
                                <p>Sucursal ID: <span>"Id registrado en BD"</span></p>
                            </div>
                        </div>

                        <a :href="route('essentials.admin.infoSucursal')">
                            <button class="btn-essentials-secundary">Ir a mi sucursal <span
                                    class="material-symbols-rounded"> outbound </span></button>
                        </a>

                    </div>
                </div>

                <!-- Modal -->
                <Modal :isOpen="showModal" titulo="Creación de una nueva sucursal" descripcion="En este apartado podrás crear una nueva sucursal de tu tienda, recuerda
            llenar todos los campos correspondientes." confirmText="Confirmar creación" @close="showModal = false"
                    @confirm="createBranch">
                    <div class="mt-[5px] flex flex-col justify-between gap-3 w-full">
                        <div class="mt-[10px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Título sucursal:</p>
                            <div class="input-insertar">
                                <input type="text" placeholder="Ponle un nombre a tu sucursal." v-model="branchTitle" />
                                <span class="material-symbols-rounded text-essentials-primary">format_italic</span>
                            </div>
                        </div>

                        <div class="mt-[5px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Descripción de la sucursal:</p>
                            <div class="input-insertar">
                                <input type="text" placeholder="Describela brevemente." v-model="branchDescription" />
                                <span class="material-symbols-rounded text-essentials-primary">format_italic</span>
                            </div>
                        </div>
                    </div>
                </Modal>
            </main>


        </div>
    </div>
</template>
