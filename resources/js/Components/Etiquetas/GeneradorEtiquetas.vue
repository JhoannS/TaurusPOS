<template>
    <div>
      <div id="etiquetas" class="pt-5 flex flex-wrap items-center justify-between">
        <div
          v-for="etiqueta in etiquetas"
          :key="etiqueta.id"
          class="w-[32.3%] m-1 p-2 rounded-lg gap-1"
        >
          <svg :id="`barcode-${etiqueta.id}`" class="w-full rounded-lg h-[100px] -z-50"></svg>
          <div class="nombre-id flex justify-between items-center mt-3">
            <p class="text-[25px]">{{ etiqueta.nombreEtiqueta }}</p>
            <p class="text-[20px]">{{ etiqueta.id }}</p>
          </div>
          <div class="fecha-us flex justify-between items-center mt-3">
            <div class="fecha">
              <p class="text-[14px] text-secundary-light">Fecha de creación:</p>
              <p class="text-[14px] text-secundary-light">{{ etiqueta.fecha }}</p>
            </div>
            <div class="uso">
              <p class="text-[14px] text-secundary-light">Precio:</p>
              <p class="text-[14px] text-secundary-light" >{{ etiqueta.precioEtiqueta }}</p>
            </div>
          </div>
          <div class="btn-accion flex justify-between items-center mt-2">
            <button 
              @click="eliminarEtiqueta(etiqueta.id)" 
              class="p-1 rounded-full border border-secundary-light flex items-center justify-center"
            >
              <span class="material-symbols-rounded text-[20px]">delete</span>
            </button>
            <button class="p-1 rounded-full border border-secundary-light flex items-center justify-center">
              <span class="material-symbols-rounded text-[20px]">edit</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
defineProps({
  etiquetas: {
    type: Array,
    required: false, // ✅ Ahora no es obligatoria
    default: () => [] // ✅ Define un array vacío como valor predeterminado
  },
  eliminarEtiqueta: {
    type: Function,
    required: false
  }
})

  </script>
  