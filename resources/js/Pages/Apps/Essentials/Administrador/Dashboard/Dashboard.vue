<script>
import { Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import BarraCalendario from '@/Components/BarraCalendario/BarraCalendario.vue';
import DetallesPlan from '@/Components/Dashboard/DetallesPlan.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import CardHistorial from '@/Components/Dashboard/Essentials/CardHistorial.vue';
</script>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import BarraCalendario from '@/Components/BarraCalendario/BarraCalendario.vue';
import DetallesPlan from '@/Components/Dashboard/DetallesPlan.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';

defineProps({
    auth: Object,
    user: Object
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
</script>

<template>
    <div>

        <Head title="Dashboard Essentials" />
        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />
            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones :auth="auth" />
                <div class="flex gap-[10px] ">
                    <div class="left w-[35%] h-[85vh] flex flex-col gap-5 justify-between px-3">
                        <div class="saludo-btn flex flex-col justify-center items-center gap-3">
                            <h4 class="text-[25px] text-center">
                                <span id="saludo">{{ saludo }}</span> {{ auth.user.nombres_ct }}, espero te encuentres
                                muy bien, ¿Listo para vender?
                            </h4>
                            <p>La aplicación más profesional del mercado.</p>
                            <button
                                class="flex items-center justify-center gap-3 shadow-essentials bg-essentials-primary py-[10px] px-[40px] rounded-xl hover:shadow-lg">
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
                        <BarraCalendario />
                    </div>

                    <div class="right w-[65%] px-3 flex flex-col ">
                        <div class="encabezadoPanelDerecho">
                            <div class="encabezado flex gap-2 items-center bg-transparent">
                                <div
                                    class="gota h-[20px] w-[20px] rounded-full bg-essentials-primary shadow-essentials">
                                </div>
                                <p class="bg-transparent">Historial de movimientos:</p>
                            </div>
                            <div class="flex items-center gap-[5px] my-[5px] bg-transparent">
                                <div class="checkbox-wrapper-16">
                                    <label class="checkbox-wrapper">
                                        <input class="checkbox-input" type="checkbox" value="Todos"
                                            name="CategoriasDash" />
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
                                        <input class="checkbox-input" type="checkbox" name="CategoriasDash"
                                            value="Ventas" />
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

                        <CardHistorial />
                        <DetallesPlan v-if="user?.tienda?.aplicacion?.plan?.detalles"
                            :detalles="user.tienda.aplicacion.plan.detalles" />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
