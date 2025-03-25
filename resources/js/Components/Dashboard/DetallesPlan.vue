<template>
  <div id="progressBarsContainer" class="space-y-4">
    <div v-for="bar in barsData" :key="bar.id" class="space-y-2">
      <!-- Título de la barra -->
      <div class="flex justify-between text-[13px] font-medium">
        <p>{{ bar.title }}</p>
        <p>{{ barTotalValue(bar) }} / {{ barTotalMax(bar) }}</p>
      </div>
      <!-- Barra de progreso -->
      <div class="w-full bg-gray-300 rounded-full h-2 overflow-hidden relative">
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
          <p>{{ segment.tag }}: <span>{{ segment.value }} / {{ segment.max }}</span></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  detalles: {
    type: Object,
    required: true
  }
});

// Convertir los datos del plan a la estructura que usa `barsData`
const barsData = computed(() => [
  {
    id: 'bar1',
    title: 'Sucursales activas:',
    segments: [
      { max: props.detalles?.cantidad_sucursales || 0, value: 10, color: 'bg-blue-500', tag: 'Sucursales' }
    ]
  },
  {
    id: 'bar2',
    title: 'Gestión usuarios:',
    segments: [
      { max: props.detalles?.cantidad_empleados || 0, value: 9999, color: 'bg-purple-500', tag: 'Empleados' },
      { max: props.detalles?.cantidad_proveedores || 0, value: 9999, color: 'bg-yellow-500', tag: 'Proveedores' }
    ]
  },
  {
    id: 'bar3',
    title: 'Facturación POS:',
    segments: [
      { max: props.detalles?.cantidad_facturacion_electronica || 0, value: 1, color: 'bg-teal-500', tag: 'Electrónica' },
      { max: props.detalles?.cantidad_facturacion_fisica || 0, value: 10, color: 'bg-pink-500', tag: 'Física' }
    ]
  },
  {
    id: 'bar4',
    title: 'Gestión de inventarios:',
    segments: [
      { max: props.detalles?.cantidad_productos || 0, value: 9999, color: 'bg-indigo-500', tag: 'Productos' },
      { max: props.detalles?.cantidad_servicios || 0, value: 9999, color: 'bg-lime-500', tag: 'Servicios' }
    ]
  },
  {
    id: 'bar5',
    title: 'Gestión de reservas y control:',
    segments: [
      { max: 1, value: props.detalles?.manejo_reservas === 'Si' ? 1 : 0, color: 'bg-green-500', tag: 'Reservas' },
      { max: 1, value: props.detalles?.manejo_pos === 'Si' ? 1 : 0, color: 'bg-red-500', tag: 'POS' },
      { max: 1, value: props.detalles?.manejo_control_gastos === 'Si' ? 1 : 0, color: 'bg-orange-500', tag: 'Control de gastos' }
    ]
  }
]);

// Función para calcular el total de valores en una barra
const barTotalValue = (bar) => {
  return bar.segments.reduce((sum, segment) => sum + segment.value, 0);
};

// Función para calcular el total máximo de una barra
const barTotalMax = (bar) => {
  return bar.segments.reduce((sum, segment) => sum + segment.max, 0);
};

// Función para calcular el porcentaje que ocupa cada segmento
const segmentPercentage = (segment, bar) => {
  const totalMax = barTotalMax(bar);
  return (segment.value / totalMax) * 100;
};
</script>
