<template>
  <div id="progressBarsContainer" class="space-y-4">
    <div v-for="bar in barsData" :key="bar.id" class="space-y-2">
      <!-- Título de la barra -->
      <div class="titulo flex justify-between text-[13px]">
        <p>{{ bar.title }}</p>
        <p>{{ barTotalValue(bar) }}</p>
      </div>
      <!-- Barra de progreso -->
      <div class="w-full bg-gray-300 rounded-full h-2 flex overflow-hidden relative">
        <div
          v-for="segment in bar.segments"
          :key="segment.tag"
          :class="[segment.color, 'h-2', 'transition-all', 'duration-500']"
          :style="{ width: segmentPercentage(segment, bar) + '%' }"
        ></div>
      </div>
      <!-- Tags descriptivos -->
      <div class="flex flex-wrap gap-3 mt-2">
        <div
          v-for="segment in bar.segments"
          :key="segment.tag"
          class="flex items-center gap-1 text-[12px]"
        >
          <div :class="['h-[12px] w-[12px] rounded-sm', segment.color]"></div>
          <p>{{ segment.tag }}: <span>{{ segment.value }}/{{ segment.max }}</span></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  barsData: {
    type: Array,
    required: true
  }
});

// Función para calcular el total de valores en una barra
const barTotalValue = (bar) => {
  return bar.segments.reduce((sum, segment) => sum + segment.value, 0);
};

// Función para calcular el porcentaje que ocupa cada segmento
const segmentPercentage = (segment, bar) => {
  const totalMax = Math.max(...bar.segments.map(s => s.max));
  return (segment.value / totalMax) * 100;
};
</script>

<style scoped>
.titulo {
  font-weight: 600;
}
</style>
