<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import Productos from '@/Components/TablaDatos/Essentials/Productos.vue';
import ExportarExcel from '@/components/ExportarExcel/ExportarExcel.vue';



export default {
    name: 'InfoSucursales',
    components: {
        Head,
    },
}
</script>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref } from 'vue';

const page = usePage();
const currentRoute = computed(() => page.url);

// Obtén los valores de la aplicación y el rol desde la página
const aplicacion = page.props.user?.tienda?.aplicacion?.nombre_app || '0';
const rol = page.props.user?.rol || '0';

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
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })" class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a :href="route('aplicacion.multisucursales', { aplicacion, rol })" class="hover:text-essentials-secundary">
                        <p>Sucursales activas</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Informacion de mi sucursal</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Gestion de sucursal</h4>
                    <div class="input-buscador">
                        <input v-model="searchQuery" type="search" placeholder="Buscar producto..." class="" />
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>



                    <a :href="route('essentials.admin.crearItem')">
                        <button class="btn-essentials-secundary">Agregar items a esta bodega<span
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
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center shadow-essentials w-10 h-10 font-bold">
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
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center shadow-essentials w-10 h-10 font-bold">
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
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center shadow-essentials w-10 h-10 font-bold">
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
