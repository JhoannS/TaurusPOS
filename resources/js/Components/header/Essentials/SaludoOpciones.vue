<script>
import { ref, onMounted, onUnmounted, computed, } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>

<script setup>
// Traer datos del usuario logueado
const props = defineProps({
    auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

// Normaliza las rutas para que la comparación funcione
const currentRoute = computed(() => new URL(page.url, window.location.origin).pathname);
const configuracionesRoute = computed(() => new URL(route('aplicacion.configuraciones', { aplicacion, rol }), window.location.origin).pathname);


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


const initials = computed(() => {
    const nombres = props.auth.user?.nombres_ct || '';
    const apellidos = props.auth.user?.apellidos_ct || '';

    // ⚡ Toma la primera letra del primer nombre y primer apellido
    const firstNameInitial = nombres.split(' ')[0]?.charAt(0).toUpperCase() || '';
    const lastNameInitial = apellidos.split(' ')[0]?.charAt(0).toUpperCase() || '';

    return firstNameInitial + lastNameInitial;
});


// ✅ Clases dinámicas según la aplicación
const colores = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'default': 'bg-gray-300 shadow-gray-300'
};
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');

const diaClase = computed(() => colores[appName.value]);
const separadorClase = computed(() => colores[appName.value]);
const gotaClase = computed(() => colores[appName.value]);
</script>

<template>
    <header class="w-full h-[100%] flex justify-between">
        <div class="date flex gap-3 items-center">
            <div class="dia flex items-center justify-center font-semibold h-10 w-7 rounded-full" :class="[diaClase]"
                id="dia">
                {{ dia }}
            </div>
            <div class="mes-año flex flex-col text-[14px] font-medium" id="mes-año">
                <span id="mes">{{ mes }}</span>
                <span id="anio">{{ anio }}</span>
            </div>
            <div :class="[separadorClase]" class="separador h-8 w-[2px] rounded-lg"></div>
            <div class="hora text-[14px]" id="hora">{{ hora }}</div>
        </div>

        <div class="options-user flex gap-2 items-center justify-center">
            <div class="btn-link-disable">
                <span class="bg-transparent material-symbols-rounded">
                    notifications
                </span>
            </div>
            <div :class="[currentRoute === configuracionesRoute ? `btn-link-${appName}` : 'btn-link-disable']">
                <a :href="route('aplicacion.configuraciones', { aplicacion, rol })"
                    class="bg-transparent flex items-center justify-center">
                    <span class="material-symbols-rounded"> settings </span>
                </a>
            </div>

            <div v-if="currentRoute !== configuracionesRoute" class="logo flex gap-3 items-center">
                <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                    :class="[gotaClase]">
                    <span class="text-white text-md font-bold">
                        {{ initials }}
                    </span>
                </div>
                <div class="logo">
                    <div v-if="auth && auth.user">
                        <h3 class="font-semibold"> {{ auth.user.nombres_ct }} {{ auth.user.apellidos_ct }}</h3>
                        <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                            {{ auth.user.rol?.tipo_rol || 'Sin rol' }}
                        </p>
                    </div>
                    <div v-else>
                        <p>Cargando información del usuario...</p>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
