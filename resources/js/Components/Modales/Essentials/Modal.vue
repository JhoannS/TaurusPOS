<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
  isOpen: Boolean, // Estado de apertura del modal
  titulo: String,
  descripcion: String,
  confirmText: {
    type: String,
    default: 'Confirmar',
  },
  auth: Object,
});

const emit = defineEmits(['close', 'confirm']);


const close = () => {
  emit('close'); // Emitir evento para cerrar el modal
};

// Definir los colores
const bgColor = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'Smart': 'bg-smart-primary shadow-smart',
    'default': 'bg-gray-300 shadow-gray-300'
};

const button = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Essentials': 'bg-essentials-primary shadow-essentials rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Machine': 'bg-machine-primary shadow-machine rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Shopper': 'bg-shopper-primary shadow-shopper rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Smart': 'bg-smart-primary shadow-smart text-mono-negro rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'default': 'bg-gray-300 shadow-gray-300'
};

const bgText = {
    'TaurusCO': 'text-universal-naranja',
    'Essentials': 'text-essentials-primary',
    'Machine': 'text-machine-primary',
    'Shopper': 'text-shopper-primary',
    'Smart': 'text-smart-primary',
    'default': 'text-gray-300'
};
const colores2 = {
    'TaurusCO': 'bg-universal-naranja',
    'Essentials': 'bg-essentials-primary',
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
            return 'hover:border-universal-naranja ';
        case 'Essentials':
            return 'hover:border-essentials-primary ';
        case 'Machine':
            return 'hover:border-machine-primary ';
        case 'Shopper':
            return 'hover:border-shopper-primary ';
        case 'Smart':
            return 'hover:border-smart-primary ';
        default:
            return 'hover:border-gray-300';
    }
});

const bgFocus = computed(() => bgColor[appName.value]);
const textFocus = computed(() => bgText[appName.value]);
const buttonFocus = computed(() => button[appName.value]);
const bg = computed(() => colores2[appName.value]);
</script>

<template>
  <div v-if="isOpen" @click="close"
    class="modal fixed inset-0 flex items-center justify-center bg-secundary-opacity backdrop-blur-sm z-50">
    <div @click.stop
      class="bg-mono-negro border-[2px] border-secundary-light text-blanco p-6 rounded-lg shadow-lg w-[90%] max-w-[600px]">
      <!-- Botón para cerrar -->
      <div class="flex items-center gap-[3px] cursor-pointer" @click="close">
        <span class="material-symbols-rounded">chevron_left</span>
        <p class="text-[12px]">Volver</p>
      </div>

      <!-- Título -->
      <div class="encabezado flex gap-2 items-center bg-transparent my-2">
        <div class="gota h-[20px] w-[20px] rounded-full" :class="[bgFocus]" ></div>
        <p class="bg-transparent text-[25px]">{{ titulo }}</p>
      </div>
      <p class="mb-[10px] text-secundary-light">{{ descripcion }}</p>

      <hr class="h-[2px] w-full" />


      <!-- Contenido dinámico -->
      <slot></slot>

      <button class="mt-4 w-full" @click="$emit('confirm')" :class="buttonFocus">
        {{ confirmText }}
      </button>
    </div>
  </div>
</template>

