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
  import { ref } from 'vue';
  
  // Datos de las barras de progreso
  const barsData = ref([
    {
      id: "bar1",
      title: "Sucursales activas:",
      segments: [
        { max: 1, value: 1, color: "bg-blue-500", tag: "Sucursal 1: 'Direccion sucursal'" },
        { max: 1, value: 1, color: "bg-green-500", tag: "Sucursal 2: 'Direccion sucursal'" }
      ],
    },
    {
      id: "bar2",
      title: "Gestión usuarios:",
      segments: [
        { max: 9999, value: 1, color: "bg-purple-500", tag: "Empleados" },
        { max: 9999, value: 1, color: "bg-yellow-500", tag: "Usuarios" },
        { max: 9999, value: 1, color: "bg-orange-500", tag: "Clientes" },
      ],
    },
    {
      id: "bar3",
      title: "Facturación POS",
      segments: [
        { max: 9999, value: 1, color: "bg-teal-500", tag: "Electronica" },
        { max: 9999, value: 1, color: "bg-pink-500", tag: "Digital" }
      ],
    },
    {
      id: "bar4",
      title: "Gestión de inventarios",
      segments: [
        { max: 9999, value: 1, color: "bg-cyan-500", tag: "Categorías" },
        { max: 9999, value: 1, color: "bg-indigo-500", tag: "Productos" },
        { max: 9999, value: 1, color: "bg-lime-500", tag: "Servicios" },
      ],
    },
    {
      id: "bar5",
      title: "Gestión de mesas:",
      segments: [
        { max: 200, value: 1, color: "bg-emerald-500", tag: "Registradas" },
      ],
    },
  ]);
  
  // Función para calcular el total de valores en una barra
  function barTotalValue(bar) {
    return bar.segments.reduce((sum, segment) => sum + segment.value, 0);
  }
  
  // Función para calcular el porcentaje que ocupa cada segmento
  function segmentPercentage(segment, bar) {
    const totalMax = Math.max(...bar.segments.map(s => s.max));
    return (segment.value / totalMax) * 100;
  }
  </script>
  