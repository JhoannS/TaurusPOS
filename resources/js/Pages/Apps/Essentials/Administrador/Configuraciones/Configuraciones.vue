<script>
import { Head, usePage, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import BarraCalendario from '@/Components/BarraCalendario/BarraCalendario.vue';
import DetallesPlan from '@/Components/Dashboard/DetallesPlan.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import CardHistorial from '@/Components/Dashboard/Essentials/CardHistorial.vue';


export default {
    name: 'Dashboard',
    components: {
        Head,
    },
}
import { defineProps, ref, onMounted, onUnmounted, computed } from 'vue';


</script>

<script setup>
const props = defineProps({
    auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

const logout = () => {
    router.visit(route('logout'), {
        method: 'post',
        preserveScroll: true,
    });
};

const initials = computed(() => {
    const nombres = props.auth.user?.nombres_ct || '';
    const apellidos = props.auth.user?.apellidos_ct || '';

    // ⚡ Toma la primera letra del primer nombre y primer apellido
    const firstNameInitial = nombres.split(' ')[0]?.charAt(0).toUpperCase() || '';
    const lastNameInitial = apellidos.split(' ')[0]?.charAt(0).toUpperCase() || '';

    return firstNameInitial + lastNameInitial;
});



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


</script>

<template>
    <div>

        <Head title="Configuraciones" />


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
                    <p class="font-bold">Configuraciones</p>
                </div>

                <div class="w-full rounded-lg border border-secundary-light min-h-[100%] my-7 bg-secundary-default p-2">
                    <div class="header bg-essentials-primary w-full h-[100px] rounded-md"></div>
                    <div class="img-nombre -mt-[50px] mx-6">

                        <div class="logo flex gap-3 items-end">
                            <!-- Contenedor del usuario -->
                            <div class="user h-[120px] w-[120px] rounded-lg overflow-hidden flex items-center justify-center bg-black shadowM ">
                                <span class="text-white text-4xl font-bold">
                                    {{ initials }}
                                </span>
                            </div>

                            <!-- Información del usuario -->
                            <div class="logo">
                                <h3 class="font-semibold text-[25px]">{{ auth.user.nombres_ct }} {{
                                    auth.user.apellidos_ct }}</h3>
                                <p class="-mt-[8px] text-secundary-light text-[16px] font-medium">
                                    {{ auth.user.rol?.tipo_rol || 'Sin rol' }}
                                </p>
                            </div>
                        </div>

                        <div class="titulos my-2">
                            <h5 class="text-[30px] font-semibold">Configuraciones</h5>
                            <p class="text-[17px] font-light">El apartado donde podras cambiar configuraciones para que
                                se ajusten a tu acomodo.</p>
                        </div>
                        <hr class="h-[2px] w-full" />

                        <div class="opciones my-4 flex flex-col gap-4">
                            <div class="tema flex justify-between items-center">
                                <div class="titulo-tema">
                                    <p class="text-[20px] font-medium">Idioma</p>
                                    <p class="font-extralight">Puedes cambiar el idioma de la aplicación (Español -
                                        Ingles)</p>
                                </div>
                                <div class="opcion flex items-center gap-3">
                                    <p>Cambiar</p>
                                    <div class="icono flex justify-center items-center h-[40px] w-[40px]">
                                        <span class="material-symbols-rounded">Language</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tema flex justify-between items-center">
                                <div class="titulo-tema">
                                    <p class="text-[20px] font-medium">Nombre de usuario</p>
                                    <p class="font-extralight">Puedes cambiar el nombre de usuario tantas veces quieras
                                    </p>
                                    <p>Nombre actual: <span>{{ auth.user.numero_documento_ct }}</span></p>
                                </div>
                                <div class="opcion flex items-center gap-3">
                                    <p>Cambiar</p>
                                    <div class="icono flex justify-center items-center h-[40px] w-[40px]">
                                        <span class="material-symbols-rounded">verified_user</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tema flex justify-between items-center">
                                <div class="titulo-tema">
                                    <p class="text-[20px] font-medium">Contraseña</p>
                                    <p class="font-extralight">Puedes cambiar tu contraseña de usuario tantas veces
                                        quieras</p>
                                    <p>Contraseña actual: <span>*******</span></p>
                                </div>
                                <div class="opcion flex items-center gap-3">
                                    <p>Cambiar</p>
                                    <div class="icono flex justify-center items-center h-[40px] w-[40px]">
                                        <span class="material-symbols-rounded">password</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tema flex justify-between items-center">
                                <div class="titulo-tema">
                                    <p class="text-[20px] font-medium">Cerrar sesion</p>
                                </div>
                                <!-- Botón para Diversidad -->

                                <button @click="logout"
                                    class="opcion flex items-center gap-3 bg-essentials-primary shadow-essentials cursor-pointer px-4 rounded-lg">
                                    <p>Salir</p>
                                    <div class="icono flex justify-center items-center h-[40px] w-[40px]">
                                        <span class="material-symbols-rounded">logout</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>


            </main>


        </div>
    </div>
</template>
