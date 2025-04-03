<script setup>
import { defineProps, computed } from 'vue';


// Definir props
const props = defineProps({
  auth: { type: Object, required: true }
});

// Definir los colores
const colores = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
  'Essentials': 'bg-essentials-primary shadow-essentials rounded-full z-10',
  'Machine': 'bg-machine-primary shadow-machine',
  'Shopper': 'bg-shopper-primary shadow-shopper',
  'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
  'default': 'bg-gray-300 shadow-gray-300'
};
const colores2 = {
  'TaurusCO': 'bg-universal-naranja',
  'Essentials': 'bg-essentials-primary rounded-full z-10',
  'Machine': 'bg-machine-primary',
  'Shopper': 'bg-shopper-primary',
  'Smart': 'bg-smart-primary',
  'default': 'bg-gray-300 shadow-gray-300'
};

// Primero definimos appName para obtener el nombre de la app
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');

// Luego definimos hoverClass usando appName
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

const bgFocus = computed(() => colores[appName.value]);
const bg = computed(() => colores2[appName.value]);
</script>

<template>
  <div class="cardInfo my-4 flex flex-col gap-2 h-[320px] max-h-[350px] overflow-y-auto scrollbar-custom">
    <div class="anuncio mr-2 flex justify-between gap-3 items-center border border-secundary-light rounded-lg p-3">
      <div class="iconoIfo flex items-center gap-3">
        <span class="material-symbols-rounded p-3 rounded-lg" :class="[bgFocus]">
          privacy_tip
        </span>
        <div class="info">
          <p>Se ha "Accion" el producto: "Nombre del producto"</p>
          <div class="creacion-id flex justify-between text-[12px]">
            <p>Creado por: <span>"Nombre usuario"</span>.</p>
            <p>ID movimiento: <span>"Número en la DB"</span>.</p>
          </div>
        </div>
      </div>
      <div class="fecha-categoria flex flex-col items-end gap-2 text-[12px]">
        <p>Fecha creación: <span>"Fecha del movimiento"</span></p>
        <p class="rounded-[5px] p-1" :class="[bgFocus]">
          Nombre Tag
        </p>
      </div>
    </div>
  </div>
</template>
