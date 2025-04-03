<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';


const props = defineProps({
    auth: { type: Object, required: true }
});


const monthNames = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

const currentDate = new Date();
const currentMonth = ref(currentDate.getMonth());
const currentYear = ref(currentDate.getFullYear());
const today = currentDate.getDate();
const startDayIndex = ref(today - 1); // Comienza desde el día actual
const daysToShow = 7;

// Computed para mostrar "Mes Año"
const monthYear = computed(() => {
    return `${monthNames[currentMonth.value]} ${currentYear.value}`;
});

// Computed para generar el arreglo de días a mostrar
const daysArray = computed(() => {
    const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    let arr = [];
    const endIndex = Math.min(startDayIndex.value + daysToShow, daysInMonth);
    for (let i = startDayIndex.value; i < endIndex; i++) {
        arr.push(i + 1);
    }
    return arr;
});

// Función para cambiar el bloque de días (avanzar o retroceder)
function changeDays(step) {
    let daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    startDayIndex.value += step * daysToShow;

    // Si superamos los días del mes, avanzamos al siguiente mes
    while (startDayIndex.value >= daysInMonth) {
        startDayIndex.value -= daysInMonth;
        currentMonth.value++;
        if (currentMonth.value > 11) {
            currentMonth.value = 0;
            currentYear.value++;
        }
        daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    }

    // Si retrocedemos más allá del inicio del mes, retrocedemos un mes
    while (startDayIndex.value < 0) {
        currentMonth.value--;
        if (currentMonth.value < 0) {
            currentMonth.value = 11;
            currentYear.value--;
        }
        daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
        startDayIndex.value += daysInMonth;
    }
}

// Función para cambiar de mes
function changeMonth(step) {
    currentMonth.value += step;
    if (currentMonth.value < 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else if (currentMonth.value > 11) {
        currentMonth.value = 0;
        currentYear.value++;
    }
    // Al cambiar de mes, reiniciamos el índice al primer día
    startDayIndex.value = 0;
}

// Definir los colores
const colores = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials rounded-full text-mono-blanco',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
    'default': 'bg-gray-300 shadow-gray-300'
};


// Primero definimos appName para obtener el nombre de la app
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgFocus = computed(() => colores[appName.value]);
</script>

<template>
    <div class="calendar flex flex-col justify-center items-center">
        <div class="month-nav flex justify-between items-center text-[12px] my-2">
            <span class="material-symbols-rounded cursor-pointer" @click="changeMonth(-1)">
                chevron_left
            </span>

            <span id="monthYear" class="mx-5">{{ monthYear }}</span>
            <span class="material-symbols-rounded cursor-pointer" @click="changeMonth(1)">
                chevron_right
            </span>
        </div>

        <div class="day-nav flex items-center justify-between bg-mono-blanco shadowM rounded-2xl p-[15px] w-full">
            <span class="material-symbols-rounded cursor-pointer text-mono-negro" @click="changeDays(-1)">
                chevron_left
            </span>

            <div class="days flex gap-8 p-[6px] text-mono-negro" id="daysContainer" v-for="day in daysArray"
                :key="day" :class="[
                    'day', 'font-bold', 'text-mono-blanco' ,
                    (day === today && currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()) ? bgFocus : ''
                ]">
                {{ day }}
            </div>

            <span class="material-symbols-rounded cursor-pointer text-mono-negro" @click="changeDays(1)">
                chevron_right
            </span>
        </div>
    </div>
</template>
