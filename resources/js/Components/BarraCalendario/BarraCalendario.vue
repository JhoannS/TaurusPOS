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

                            <!-- <div class="weekdays flex gap-5">
              <span>Dom</span><span>Lun</span><span>Mar</span><span>Mié</span
              ><span>Jue</span><span>Vie</span><span>Sáb</span>
            </div> -->

                            <div
                                class="day-nav flex justify-between bg-mono-blanco shadowM rounded-2xl p-[15px] w-full">
                                <span class="material-symbols-rounded cursor-pointer text-mono-negro"
                                    @click="changeDays(-1)">
                                    chevron_left
                                </span>

                                <div class="days flex gap-8 bg-transparent text-mono-negro" id="daysContainer"
                                    v-for="day in daysArray" :key="day" :class="[
                                        'day', 'bg-transparent', 'font-bold',
                                        { 'text-red-400': day === today && currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear() }
                                    ]">
                                    {{ day }}
                                </div>
                                <span class="material-symbols-rounded cursor-pointer text-mono-negro"
                                    @click="changeDays(1)">
                                    chevron_right
                                </span>
                            </div>
                        </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
//
// Funcionalidad del calendario
//
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
</script>