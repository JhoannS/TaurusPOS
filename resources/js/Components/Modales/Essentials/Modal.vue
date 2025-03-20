<template>
    <div 
      v-if="isOpen" 
      @click="close" 
      class="modal fixed inset-0 flex items-center justify-center bg-secundary-opacity backdrop-blur-sm z-50"
    >
      <div 
        @click.stop 
        class="bg-mono-negro border-[2px] border-secundary-light text-blanco p-6 rounded-lg shadow-lg w-[90%] max-w-[600px]"
      >
        <!-- Botón para cerrar -->
        <div class="flex items-center gap-[3px] cursor-pointer" @click="close">
          <span class="material-symbols-rounded">chevron_left</span>
          <p class="text-[12px]">Volver</p>
        </div>
  
        <!-- Título -->
        <div class="encabezado flex gap-2 items-center bg-transparent my-2">
          <div class="gota h-[20px] w-[20px] rounded-full bg-essentials-primary shadow-essentials"></div>
          <p class="bg-transparent text-[25px]">{{ titulo }}</p>
        </div>
        <p class="mb-[10px] text-secundary-light">{{ descripcion }}</p>

      <hr class="h-[2px] w-full" />
        
  
        <!-- Contenido dinámico -->
        <slot></slot>
  
        <button 
          class="btn-essentials mt-4 w-full" 
          @click="$emit('confirm')"
        >
          {{ confirmText }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    isOpen: Boolean, // Estado de apertura del modal
    titulo: String,
    descripcion: String,
    confirmText: {
      type: String,
      default: 'Confirmar',
    },
  });
  
  const emit = defineEmits(['close', 'confirm']);
  
  const close = () => {
    emit('close'); // Emitir evento para cerrar el modal
  };
  </script>
  
  <style scoped>
  .modal {
    transition: opacity 0.3s ease;
  }
  </style>
  