<script setup>
import { defineProps, computed } from 'vue';


// Definir props
const props = defineProps({
  auth: { type: Object, required: true },
   auditorias: Array
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
  
</template>
