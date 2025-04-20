<script>
import { ref, onMounted, onUnmounted, computed, } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>

<script setup>
// Traer datos del usuario logueado
const props = defineProps({
    auth: { type: Object, required: true },
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

// Normaliza las rutas para que la comparación funcione
const currentRoute = computed(() => new URL(page.url, window.location.origin).pathname);
const configuracionesRoute = computed(() => new URL(route('aplicacion.configuraciones', { aplicacion, rol }), window.location.origin).pathname);
const historialRoute = computed(() => new URL(route('aplicacion.historial', { aplicacion, rol }), window.location.origin).pathname);


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

const inicialesNombreTienda = computed(() => {
  const nombreTienda =  props.auth.user?.tienda?.nombre_tienda || '';

  const inicialTienda = nombreTienda.split(' ')[0]?.charAt(0).toUpperCase() || '';
  return inicialTienda;
});

// ✅ Clases dinámicas según la aplicación
const colores = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja rounded-full',
    'Essentials': 'bg-essentials-primary shadow-essentials rounded-full z-10 ',
    'Machine': 'bg-machine-primary shadow-machine rounded-full',
    'Shopper': 'bg-shopper-primary shadow-shopper rounded-full',
    'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
    'default': 'bg-gray-300 shadow-gray-300'
};
const colores2 = {
    'TaurusCO': 'bg-universal-naranja rounded-full',
    'Essentials': 'bg-essentials-primary rounded-full z-10',
    'Machine': 'bg-machine-primary rounded-full',
    'Shopper': 'bg-shopper-primary rounded-full',
    'Smart': 'bg-smart-primary rounded-full',
    'default': 'bg-gray-300 shadow-gray-300'
};

// Computed para la clase de hover
const hoverClass = computed(() => {
    switch (appName.value) {
        case 'TaurusCO':
            return 'hover:bg-universal-naranja';
        case 'Essentials':
            return 'hover:bg-essentials-primary';
        case 'Machine':
            return 'hover:bg-machine-primary';
        case 'Shopper':
            return 'hover:bg-shopper-primary';
        case 'Smart':
            return 'hover:bg-smart-primary hover:text-mono-negro';
        default:
            return 'hover:bg-gray-300';
    }
});
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');

const bgFocus = computed(() => colores[appName.value]);
const bg = computed(() => colores2[appName.value]);
const diasRestantes = computed(() => props.auth.user?.tienda?.pagos_membresia?.dias_restantes ?? 'sin días');

</script>

<template>
    <div class="header flex items-center gap-3 p-1">
        <div class="left p-2  bg-secundary-opacity w-[20%] rounded-md">
            <div class="infoTienda flex gap-2">
                <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                        :class="[bgFocus]">
                        <span class="text-md font-bold">
                            {{ inicialesNombreTienda }}
                        </span>
                    </div>
                <div class="logo">
                    <div v-if="auth && auth.user">
                        <h3 class="font-semibold">{{ auth.user.tienda?.nombre_tienda || 'Sin tienda' }}</h3>
                        <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                            {{ auth.user.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }} -
                            {{ diasRestantes === 999999 ? "Membresía Infinita" : diasRestantes + " días restantes." }}
                        </p>
                    </div>

                    <div v-else>
                        <p>Cargando información del usuario...</p>
                    </div>
                </div>

            </div>
        </div>
        <header
            class="p-2 bg-secundary-opacity rounded-md w-[80%] flex items-center justify-between">
            <div class="date flex gap-3 items-center">
                <div class="dia flex items-center justify-center font-semibold h-10 w-7 rounded-full" :class="[bgFocus]"
                    id="dia">
                    {{ dia }}
                </div>
                <div class="mes-año flex flex-col text-[14px] font-medium" id="mes-año">
                    <span id="mes">{{ mes }}</span>
                    <span id="anio">{{ anio }}</span>
                </div>
                <div :class="[bgFocus]" class="separador h-8 w-[2px] rounded-lg"></div>
                <div class="hora text-[14px]" id="hora">{{ hora }}</div>
            </div>

            <div class="options-user flex gap-2 items-center justify-center">
                <div
                    :class="[currentRoute === historialRoute ? bgFocus : 'cursor-pointer rounded-full', hoverClass]">
                    <a :href="route('aplicacion.historial', { aplicacion, rol })"
                        class="flex items-center p-[6px] rounded-full justify-center">
                        <span class="material-symbols-rounded"> history </span>
                    </a>
                </div>

                <div :class="['cursor-pointer', 'rounded-full', hoverClass]"
                    class="flex items-center p-[6px] justify-center">
                    <span class="bg-transparent material-symbols-rounded">
                        notifications
                    </span>
                </div>

                <div
                    :class="[currentRoute === configuracionesRoute ? bgFocus : 'cursor-pointer rounded-full', hoverClass]">
                    <a :href="route('aplicacion.configuraciones', { aplicacion, rol })"
                        class="flex items-center p-[6px] rounded-full justify-center">
                        <span class="material-symbols-rounded"> settings </span>
                    </a>
                </div>

                <div v-if="currentRoute !== configuracionesRoute" class="logo flex gap-3 items-center">
                    <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                        :class="[bgFocus]">
                        <span class="text-md font-bold">
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
    </div>


</template>
