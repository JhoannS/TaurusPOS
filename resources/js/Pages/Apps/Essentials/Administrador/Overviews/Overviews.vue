<script>
import { ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';


</script>

<script setup>


const props = defineProps({
    auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol


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

        <Head title="Overviews" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />

            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones :auth="auth" />

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px]">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Overviews y gestion de caja</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Overview de tienda</h4>
                    <div class="input-buscador">
                        <input type="search" placeholder="Buscar contenido...">
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>

                    <!-- Botón para abrir el modal -->
                    <button class="btn-essentials">
                        Exportar informacion del día
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>
                </div>

                <div class="tarjetas-overviews my-7 flex w-full gap-2 justify-between ">
                    <div class="border border-secundary-light rounded-lg  w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Efectivo</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-6 h-6 font-bold">
                            0</div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Nequi</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-6 h-6 font-bold">
                            0</div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Daviplata</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-6 h-6 font-bold">
                            0</div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Tarjeta</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div
                            class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-6 h-6 font-bold">
                            0</div>
                    </div>
                </div>

                <div class="parentOver flex justify-between gap-4">
                    <div class="w-[60%]">
                        <div class="encabezado flex justify-between items-center mb-3">
                            <h2 class="text-blanco text-[20px] font-semibold">Gestión de caja</h2>
                            <span class="material-symbols-rounded">receipt_long</span>
                        </div>

                        <div class="ticket w-full flex justify-between items-center gap-5">
                            <div
                                class=" w-[33.3%] relative cursor-pointer border border-secundary-opacity  hover:border-essentials-primary transition-all rounded-[5px] bg-secundary-opacity text-center text-blanco text-[12px] p-3">
                                <div
                                    class="absolute shadow-essentials w-[15px] h-[15px] bg-essentials-primary -left-[8px] top-[40%] rounded-full">
                                </div>
                                <p class="bg-transparent">Orden #1</p>
                                <p class="bg-transparent">14:20:45</p>
                                <p class="bg-transparent">Fecha creación: Nov 29,2024</p>
                            </div>
                            <div
                                class=" w-[33.3%] relative cursor-pointer border border-secundary-opacity  hover:border-essentials-primary transition-all rounded-[5px] bg-secundary-opacity text-center text-blanco text-[12px] p-3">
                                <div
                                    class="absolute shadow-essentials w-[15px] h-[15px] bg-essentials-primary -left-[8px] top-[40%] rounded-full">
                                </div>
                                <p class="bg-transparent">Orden #2</p>
                                <p class="bg-transparent">14:20:45</p>
                                <p class="bg-transparent">Fecha creación: Nov 29,2024</p>
                            </div>
                            <div
                                class=" w-[33.3%] relative cursor-pointer border border-secundary-opacity  hover:border-essentials-primary transition-all rounded-[5px] bg-secundary-opacity text-center text-blanco text-[12px] p-3">
                                <div
                                    class="absolute shadow-essentials w-[15px] h-[15px] bg-essentials-primary -left-[8px] top-[40%] rounded-full">
                                </div>
                                <p class="bg-transparent">Orden #3</p>
                                <p class="bg-transparent">14:20:45</p>
                                <p class="bg-transparent">Fecha creación: Nov 29,2024</p>
                            </div>
                        </div>
                        <div id="mensaje-no-resultados"
                            class="hidden text-center mt-10 font-bold text-[35px] text-secundary-light fade-in">No hay
                            coincidencias.</div>
                    </div>

                    <div class="w-[40%]">
                        <div class="flex justify-between items-center">
                            <p class="text-blanco text-[25px] font-semibold">Metodo de compra</p>
                            <div class="flex justify-between items-center text-blanco gap-3 cursor-pointer">
                                <span class="text-[12px]">Descargar PDF</span>
                                <span class="material-symbols-rounded">downloading</span>
                            </div>
                        </div>

                        <div class=" text-blanco text-[17px] font-medium my-[15px]">
                            <p>Orden #98979778</p>
                            <div class="flex justify-between items-center">
                                <p>Fecha de creación: <span>Nov 29, 2024</span></p>
                                <p>14:20:23</p>
                            </div>
                        </div>

                        <hr class="h-[2px] w-full my-[10px]" />

                        <div class="text-blanco flex items-center justify-between text-[14px]">
                            <p class="flex items-center gap-2">Método de pago:
                                <span class="material-symbols-rounded text-[15px] text-essentials-primary">
                                    account_balance_wallet
                                </span>
                                Efectivo
                            </p>
                            <p class="flex items-center gap-2">Monto a pagar: <span
                                    class="material-symbols-rounded text-[15px] text-essentials-primary">payments</span>
                                $18.400
                            </p>
                        </div>

                        <div class="text-blanco flex items-center justify-between text-[14px]">
                            <p class="flex items-center gap-2">Dinero recibido: <span
                                    class="material-symbols-rounded text-[15px] text-essentials-primary">payments</span>
                                $20.000
                            </p>
                            <p class="flex items-center gap-2">Dinero a devolver: <span
                                    class="material-symbols-rounded text-[15px] text-essentials-primary">payments</span>
                                $600
                            </p>
                        </div>

                        <hr class="h-[2px] w-full my-[10px]" />

                        <div class="px-[10px] overflow-x-auto custom-scrollbar text-blanco" id="facturacion">
                            <div class="flex justify-between items-center gap-[8px] mt-[16px]">
                                <div class="flex items-center gap-[5px]">

                                    <div
                                        class="w-[30px] h-[30px] p-1 bg-blanco_opacidad rounded-[100%] flex items-center justify-center">
                                        <img src="" alt="img producto" width="25px" height="25px"
                                            class="rounded-[100%] h-[25px] w-[25px]" />
                                    </div>

                                    <div class="flex flex-col">
                                        <p>Hamburguesa</p>
                                        <p class="text-[10px] -mt-[3px]">Sin comentarios</p>
                                    </div>


                                </div>

                                <p class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-[15px] text-essentials-primary">
                                        pin
                                    </span>
                                    x1
                                </p>

                                <p class="number">$18.800</p>
                            </div>
                        </div>
                        <div class="flex items-center text-[14px] my-[10px]">
                            <p class="flex items-center gap-2">Comentario de la orden: <span
                                    class="material-symbols-rounded text-[15px] text-essentials-primary">format_italic</span>
                                Comentario del cliente</p>
                        </div>

                        <div class="estado-orden flex items-center gap-2">
                            <div class="gota h-5 w-5 bg-semaforo-verde shadow-verde rounded-full"></div>
                            <p>Finalizado</p>
                        </div>
                    </div>
                </div>

            </main>


        </div>
    </div>
</template>
