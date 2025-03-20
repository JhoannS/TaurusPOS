<script setup>
import { computed } from 'vue';
import logo from '@images/logoTC.svg'


const props = defineProps({
  productos: {
    type: Array,
    default: () => [
      {
        id: 1,
        nombre: 'Producto A',
        descripcion: 'Descripción del Producto A',
        categoria: 'Categoría 1',
        receta: 'Receta A',
        stock: 8,
        estado: 'Activo',
        precio_final: 1599,
        imagen: "logo"
      },
      {
        id: 2,
        nombre: 'Producto B',
        descripcion: 'Descripción del Producto B',
        categoria: 'Categoría 2',
        receta: 'Receta B',
        stock: 12,
        estado: 'Suspendido',
        precio_final: 2550,
        imagen: "logo"

      },
      {
        id: 3,
        nombre: 'Producto C',
        descripcion: 'Descripción del Producto C',
        categoria: 'Categoría 3',
        receta: 'Receta C',
        stock: 16,
        estado: 'Inactivo',
        precio_final: 3000,
        imagen: "logo"

      }
    ]
  },
  searchQuery: {
    type: String,
    default: ''
  }
});

const filteredProductos = computed(() => {
  if (!props.productos || !Array.isArray(props.productos)) return [];
  if (!props.searchQuery.trim()) return props.productos;

  const query = props.searchQuery.toLowerCase().trim();

  return props.productos.filter(producto => {
    return (
      // Búsqueda exacta en valores específicos
      String(producto.id).toLowerCase() === query ||
      producto.nombre.toLowerCase().includes(query) ||
      producto.descripcion.toLowerCase().includes(query) ||
      producto.categoria.toLowerCase().includes(query) ||
      producto.estado.toLowerCase() === query || // Busca el estado completo, no fragmentos
      producto.receta.toLowerCase().includes(query) ||
      String(producto.stock).toLowerCase() === query ||
      String(producto.precio_final).toLowerCase() === query
    );
  });
});

function getStockClass(stock) {
  if (stock <= 9) return 'bg-semaforo-rojo';
  if (stock >= 10 && stock <= 14) return 'bg-semaforo-amarillo';
  if (stock >= 15) return 'bg-semaforo-verde';
}

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
    <table class="w-full border-collapse" id="tabla">
      <thead>
        <tr class="border border-secundary-light">
          <th class="p-2 text-left">PLU</th>
          <th class="p-2 text-left">Imagen</th>
          <th class="p-2 text-left">Nombre</th>
          <th class="p-2 text-left">Descripción</th>
          <th class="p-2 text-left">Categoría</th>
          <th class="p-2 text-left">Receta</th>
          <th class="p-2 text-left">Stock</th>
          <th class="p-2 text-left">Estado</th>
          <th class="p-2 text-left">Precio final</th>
          <th class="p-2 text-left">Acciones</th>
        </tr>
      </thead>
      <transition-group
        tag="tbody"
        @before-enter="handleBeforeEnter"
        @before-leave="handleBeforeLeave"
      >
        <tr v-for="producto in filteredProductos" :key="producto.id">
          <td class="text-[14px] p-2">{{ producto.id }}</td>
          <td class="text-[14px] p-2">
            <img :src="logo" class="rounded-lg" width="45" height="40" alt="Imagen de producto" />
          </td>
          <td class="text-[14px] p-2">{{ producto.nombre }}</td>
          <td class="text-[14px] p-2">{{ producto.descripcion }}</td>
          <td class="text-[14px] p-2">{{ producto.categoria }}</td>
          <td class="text-[14px] p-2">{{ producto.receta }}</td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getStockClass(producto.stock)">
              {{ producto.stock }} unidades
            </span>
          </td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoClass(producto.estado)">
              {{ producto.estado }}
            </span>
          </td>
          <td class="text-[14px] p-2">
            ${{ producto.precio_final.toFixed(0) }}
          </td>
          <td>
            <div class="flex gap-2 items-center">
              <span class="material-symbols-rounded hover:text-essentials-primary cursor-pointer">
                delete
              </span>
              <span class="material-symbols-rounded hover:text-blue-400 cursor-pointer">
                eye_tracking
              </span>
            </div>
          </td>
        </tr>
      </transition-group>
    </table>

    <div v-if="filteredProductos.length === 0" class="text-center mt-10 font-bold text-2xl text-gray-500">
      No hay coincidencias.
    </div>
  </div>
</template>
