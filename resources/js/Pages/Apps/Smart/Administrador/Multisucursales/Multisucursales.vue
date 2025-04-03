<script setup>
import { ref, computed } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';

const showModal = ref(false);
const props = defineProps({
  auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol


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
</script>

<template>
    <div>

        <Head title="Mis bodegas Essentials" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth"/>

            <main class="w-full h-[100%] px-[40px] bg-transparent">
                <SaludoOpciones :auth="auth"/>

                <!-- navegable -->
                <div class="options flex gap-1 items-center my-3 text-[14px]">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })" class="hover:text-essentials-secundary">
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
                        <span class="material-symbols-rounded" :class="[textFocus]">travel_explore</span>
                    </div>

                    <!-- Botón para abrir el modal -->
                    <button @click="showModal = true" class="" :class="[buttonFocus]">
                        Crear nueva sucursal
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>
                </div>

                <div class="tarjetas-sucursales my-7">
                    <div class="card-sucursal flex flex-col gap-2  max-w-[25%] rounded-lg p-3">
                        <img src="https://images.unsplash.com/photo-1741851374430-d242e0dcd70c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="img de ref local"
                            class="rounded-lg">
                        <div class="icono-id-nombre flex gap-2 ">
                            <span
                                class="material-symbols-rounded flex justify-center items-center h-10 w-10 text-[14px] rounded-lg">
                                swap_horiz </span>
                            <div class="idd-nombre text-[14px]">
                                <p>(Titulo de la sucursal)</p>
                                <p>Sucursal ID: <span>"Id registrado en BD"</span></p>
                            </div>
                        </div>

                        <a >
                            <button class="" :class="[buttonFocus]">Ir a mi sucursal <span
                                    class="material-symbols-rounded"> outbound </span></button>
                        </a>

                    </div>
                </div>

                <!-- Modal -->
                <Modal :auth="auth" :isOpen="showModal" titulo="Creación de una nueva sucursal" descripcion="En este apartado podrás crear una nueva sucursal de tu tienda, recuerda
            llenar todos los campos correspondientes." confirmText="Confirmar creación" @close="showModal = false"
                    @confirm="createBranch">
                    <div class="mt-[5px] flex flex-col justify-between gap-3 w-full">
                        <div class="mt-[10px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Título sucursal:</p>
                            <div class="input-insertar">
                                <input type="text" placeholder="Ponle un nombre a tu sucursal." v-model="branchTitle" />
                                <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                            </div>
                        </div>

                        <div class="mt-[5px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Descripción de la sucursal:</p>
                            <div class="input-insertar">
                                <input type="text" placeholder="Describela brevemente." v-model="branchDescription" />
                                <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                            </div>
                        </div>
                    </div>
                </Modal>
            </main>


        </div>
    </div>
</template>
