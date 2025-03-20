<template>
    <header class="w-full h-[100%] flex justify-between mb-[40px]">
        <div class="date flex gap-3 items-center">
            <div class="dia flex items-center justify-center font-semibold h-10 w-7 shadow-essentials bg-essentials-primary rounded-full"
                id="dia">
                {{ dia }}
            </div>
            <div class="mes-año flex flex-col text-[14px] font-medium" id="mes-año">
                <span id="mes">{{ mes }}</span>
                <span id="anio">{{ anio }}</span>
            </div>
            <div class="separador h-8 w-[2px] bg-essentials-primary rounded-lg"></div>
            <div class="hora text-[14px]" id="hora">{{ hora }}</div>
        </div>

        <div class="options-user flex gap-2 items-center justify-center">
            <div class="btn-link-disable">
                <span class="bg-transparent material-symbols-rounded">
                    notifications
                </span>
            </div>
            <div class="btn-link-disable">
                <span class="bg-transparent material-symbols-rounded">
                    settings
                </span>
            </div>
            <div class="logo flex gap-3 items-center">
                <div class="gota h-10 w-10 shadow-essentials bg-essentials-primary rounded-full"></div>
                <div class="logo">
                    <div v-if="auth && auth.user">
                        <h3 class="font-semibold"> {{ auth.user.nombres_ct }}</h3>
                        <p class="-mt-[8px] text-secundary-light text-[13px] font-medium">
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

<script setup>

import { ref, onMounted, onUnmounted } from 'vue';



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

// No se necesita 'export default' en este caso

// Recibe el prop 'auth' desde el componente padre
defineProps({
    auth: Object,
})
</script>