<template>
  <div v-if="isOpen" @click="close"
    class="modal fixed inset-0 flex items-center justify-center bg-secundary-opacity backdrop-blur-sm z-50">
    <div @click.stop
      class="bg-mono-negro border-[2px] border-secundary-light text-blanco p-6 rounded-lg shadow-lg w-[90%] max-w-[95%] max-h-[80vh] overflow-auto scrollbar-custom">

      <div class="flex items-center justify-between">
        <div class="flex items-center gap-[3px] cursor-pointer" @click="close">
        <span class="material-symbols-rounded">chevron_left</span>
        <p class="text-[12px]">Volver</p>
      </div>
      <span class="material-symbols-rounded text-[18px] text-semaforo-amarillo  cursor-pointer"  @click="editUser(detalleCliente)">ink_marker</span>
      </div>
      
      <slot></slot>

    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  isOpen: Boolean, 
});

const emit = defineEmits(['close', 'confirm']);

const close = () => {
  emit('close');
};


</script>