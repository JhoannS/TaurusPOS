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

    const firstNameInitial = nombres.split(' ')[0]?.charAt(0).toUpperCase() || '';
    const lastNameInitial = apellidos.split(' ')[0]?.charAt(0).toUpperCase() || '';

    return firstNameInitial + lastNameInitial;
});

const coloresBg = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
  'Essentials': 'bg-essentials-primary shadow-essentials',
  'Machine': 'bg-machine-primary shadow-machine',
  'Shopper': 'bg-shopper-primary shadow-shopper',
  'default': 'bg-gray-300 shadow-gray-300'
};

const coloresTexto = {
  'TaurusCO': 'text-universal-naranja',
  'Essentials': 'text-essentials-primary',
  'Machine': 'text-machine-primary',
  'Shopper': 'text-shopper-primary',
  'default': 'text-gray-500'
};

const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgClase = computed(() => coloresBg[appName.value]);
const textoClase = computed(() => coloresTexto[appName.value]);
const gotaClase = computed(() => coloresBg[appName.value]);


</script>

<template>
    <div>

        <Head title="Configuraciones" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />



            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones :auth="auth" />

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px] mt-4">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Información de mi cuenta</p>
                </div>

                <div class="flex justify-between items-center w-full rounded-lg border border-secundary-light min-h-[100%] my-7 bg-secundary-default p-2">

                    <div class="leftUser">

                    </div>
                </div>

                <div class="w-full rounded-lg border border-secundary-light min-h-[100%] my-7 bg-secundary-default p-2">
                    <div class="header w-full h-[100px] rounded-md" :class="[bgClase]"></div>
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
                            <h5 class="text-[30px] font-semibold">Información de mi cuenta</h5>
                            <p class="text-[17px] font-light">El apartado donde podras observar mas a detalle sobre tu cuenta.</p>
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
