<script setup>
import { computed } from 'vue';

const props = defineProps({
  clientes: {
    type: Array,
    default: () => []
  },
  searchQuery: {
    type: String,
    default: ''
  }
});

const filteredClientes = computed(() => {
  if (!props.searchQuery.trim()) return props.clientes;

  const query = props.searchQuery.toLowerCase().trim();

  return props.clientes.filter(cliente => {
    return (
      String(cliente.id).toLowerCase().includes(query) ||
      cliente.nombre?.toLowerCase().includes(query) ||
      cliente.tienda?.toLowerCase().includes(query) ||
      cliente.telefono?.toLowerCase().includes(query) ||
      cliente.token?.toLowerCase().includes(query) ||
      cliente.aplicacion?.toLowerCase().includes(query) ||
      cliente.membresia?.toLowerCase().includes(query) ||
      String(cliente.precio).toLowerCase().includes(query) ||
      cliente.estado?.toLowerCase().includes(query)
    );
  });
});

function getEstadoClass(estado) {
  if (estado === 'Inactivo') return 'bg-semaforo-rojo';
  if (estado === 'Suspendido') return 'bg-semaforo-amarillo';
  if (estado === 'Activo') return 'bg-semaforo-verde';
}

function handleBeforeEnter(el) {
  el.classList.add('animate-fadeIn');
}

function handleBeforeLeave(el) {
  el.classList.add('animate-fadeOut');
}
</script>

<template>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse">
      <thead>
        <tr class="border border-secundary-light">
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Nombre</th>
          <th class="p-2 text-left">Tienda</th>
          <th class="p-2 text-left">Teléfono</th>
          <th class="p-2 text-left">Token</th>
          <th class="p-2 text-left">Aplicación</th>
          <th class="p-2 text-left">Membresía</th>
          <th class="p-2 text-left">Precio</th>
          <th class="p-2 text-left">Estado</th>
          <th class="p-2 text-left">Acciones</th>
        </tr>
      </thead>
      <transition-group tag="tbody" @before-enter="handleBeforeEnter" @before-leave="handleBeforeLeave">
        <tr v-for="cliente in filteredClientes" :key="cliente.id">
          <td class="text-[14px] p-2">{{ cliente.id }}</td>
          <td class="text-[14px] p-2">{{ cliente.nombre }}</td>
          <td class="text-[14px] p-2">{{ cliente.tienda || 'Sin tienda' }}</td>
          <td class="text-[14px] p-2">{{ cliente.telefono }}</td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoClass(cliente.token)">
              {{ cliente.token }}
            </span>
          </td>
          <td class="text-[14px] p-2">{{ cliente.aplicacion || 'Sin aplicación' }}</td>
          <td class="text-[14px] p-2">{{ cliente.membresia || 'Sin membresía' }}</td>
          <td class="text-[14px] p-2">${{ cliente.precio.toFixed(0) }}</td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoClass(cliente.estado)">
              {{ cliente.estado }}
            </span>
          </td>
          <td>
            <div class="flex gap-2 items-center">
              <span class="material-symbols-rounded hover:text-red-500 cursor-pointer">
                delete
              </span>
              <span class="material-symbols-rounded hover:text-blue-400 cursor-pointer">
                visibility
              </span>
            </div>
          </td>
        </tr>
      </transition-group>
    </table>

    <div v-if="filteredClientes.length === 0" class="text-center mt-10 font-bold text-2xl text-gray-500">
      No hay coincidencias.
    </div>
  </div>
</template>
