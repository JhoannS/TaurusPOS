<template>
    <div v-if="isOpen" @click="close"
         class="modal fixed inset-0 flex items-center justify-center bg-secundary-opacity backdrop-blur-sm z-50">
      <div @click.stop class="bg-mono-negro border-[2px] border-secundary-light text-blanco p-6 rounded-lg shadow-lg w-[90%] max-w-[95%] max-h-[80vh] overflow-auto scrollbar-custom">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-[3px] cursor-pointer" @click="close">
            <span class="material-symbols-rounded">chevron_left</span>
            <p class="text-[12px]">Volver</p>
          </div>
          <p class="text-lg font-bold">Editar Cliente</p>
        </div>
        <form @submit.prevent="submitEdit">
          <div class="mb-4">
            <label class="block mb-1">Nombres:</label>
            <input v-model="form.nombres_ct" type="text" class="w-full p-2 rounded border" />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Apellidos:</label>
            <input v-model="form.apellidos_ct" type="text" class="w-full p-2 rounded border" />
          </div>
          <!-- Agrega más campos según sea necesario -->
          <button type="submit" class="btn-primary">Guardar cambios</button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, reactive, watch } from 'vue';
  import axios from 'axios';
  import { route } from 'ziggy-js';
  
  const props = defineProps({
    isOpen: Boolean,
    detalleCliente: Object,
  });
  const emit = defineEmits(['close', 'updated']);
  
  // Creamos un objeto reactivo para el formulario
  const form = reactive({
    nombres_ct: '',
    apellidos_ct: '',
    // Otros campos a editar...
  });
  
  // Cuando el detalle del cliente cambia, llenamos el formulario
  watch(() => props.detalleCliente, (newVal) => {
    if (newVal) {
      form.nombres_ct = newVal.nombres_ct;
      form.apellidos_ct = newVal.apellidos_ct;
      // Cargar otros campos según sea necesario
    }
  }, { immediate: true });
  
  const close = () => {
    emit('close');
  };
  
  const submitEdit = () => {
    // Asumiendo que tienes una ruta para actualizar el cliente:
    axios.put(route('clientes.update', { id: props.detalleCliente.id }), form)
      .then(response => {
        // Emite el evento 'updated' con los datos actualizados para que el padre refresque la lista
        emit('updated', response.data);
        close();
      })
      .catch(error => {
        console.error('Error al actualizar el cliente:', error);
      });
  };
  </script>
  