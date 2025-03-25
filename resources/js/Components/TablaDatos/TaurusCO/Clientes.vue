<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/es';
import ModalDetalles from '@/Components/Modales/ModalDetallesClientes.vue';


const props = defineProps({
  auth: { type: Object, required: true },
  clientes: { type: Array, default: () => [] },
  searchQuery: { type: String, default: '' },
  userId: {
    type: Number,
    required: true
  },
  aplicacion: {
    type: String,
    required: true
  },
  rol: {
    type: Object, 
    required: true
  }
})

function getEstadoClass(estado) {
  if (estado === 'Inactivo') return 'bg-semaforo-rojo';
  if (estado === 'Suspendido') return 'bg-semaforo-amarillo';
  if (estado === 'Activo') return 'bg-semaforo-verde';
  return '';
}

function getEstadoTokenClass(token) {
  if (token === 'Inactivo' || token === 'Sin estado') return 'bg-semaforo-rojo';
  if (token === 'Suspendido') return 'bg-semaforo-amarillo';
  if (token === 'Activo') return 'bg-semaforo-verde';
  return '';
}

const filteredClientes = computed(() => {
  if (!props.clientes || !Array.isArray(props.clientes)) return [];
  if (!props.searchQuery.trim()) return props.clientes;

  const query = props.searchQuery.toLowerCase().trim();

  return props.clientes.filter(cliente => {
    return (
      String(cliente.id).toLowerCase().includes(query) ||
      cliente.nombre_completo?.toLowerCase().includes(query) ||
      cliente.nombre_tienda?.toLowerCase().includes(query) ||
      cliente.telefono?.toLowerCase().includes(query) ||
      cliente.token?.toLowerCase().includes(query) ||
      cliente.aplicacion?.toLowerCase().includes(query) ||
      cliente.membresia?.toLowerCase().includes(query) ||
      String(cliente.precio)?.toLowerCase().includes(query) ||
      cliente.estado_token?.toLowerCase().includes(query)
    );
  });
});

dayjs.locale('es');
const formatFecha = (fecha) => {
  if (!fecha || !dayjs(fecha).isValid()) return 'Sin fecha';
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a');
};


const detalleCliente = ref(null);
const showModal = ref(false);

function openDetalle(clienteId) {
  axios.get(route('aplicacion.dashboard.detalle', {
    aplicacion: props.auth.user.tienda.aplicacion.nombre_app,
    rol: props.auth.user.rol.id,
    idCliente: clienteId
  }))
    .then(response => {
      detalleCliente.value = response.data;
      showModal.value = true;
    })
    .catch(error => {
      console.error('Error al cargar los detalles del cliente:', error);
    });
}
function closeModal() {
  showModal.value = false;
  detalleCliente.value = null;
}


const confirmDelete = (id) => {
  if (id === props.userId) {
    alert('❌ No puedes eliminar tu propia cuenta.')
    return
  }

  if (confirm('⚠️ ¿Estás seguro de que deseas eliminar este cliente?')) {
    deleteCliente(id) 
  }
}

const deleteCliente = (id) => {
  if (!props.aplicacion || !props.rol) {
    console.error('❌ Faltan parámetros de aplicación o rol');
    return;
  }

  router.delete(route('clientes.destroy', {
    aplicacion: props.aplicacion,
    rol: props.rol,
    id: id
  }, {
    replace: true, 
    prefix: 'dashboard' 
  }), {
    onSuccess: () => {
      alert('✅ Cliente eliminado correctamente.');
    },
    onError: (error) => {
      console.error('❌ Error:', error);
      alert('❌ Error al eliminar el cliente.');
    }
  });
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
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Nombres</th>
          <th class="p-2 text-left">Teléfono</th>
          <th class="p-2 text-left">Tienda</th>
          <th class="p-2 text-left">Aplicación</th>
          <th class="p-2 text-left">Membresía</th>
          <th class="p-2 text-left">Precio</th>
          <th class="p-2 text-left">Token</th>
          <th class="p-2 text-left">Creación</th>
          <th class="p-2 text-left">Acciones</th>
        </tr>
      </thead>
      <transition-group tag="tbody" @before-enter="handleBeforeEnter" @before-leave="handleBeforeLeave">
        <tr v-for="cliente in filteredClientes" :key="cliente.id">
          <td class="text-[14px] p-2">{{ cliente.id }}</td>
          <td class="text-[14px] p-2">{{ cliente.nombre_completo }}</td>
          <td class="text-[14px] p-2">{{ cliente.telefono }}</td>
          <td class="text-[14px] p-2">{{ cliente.nombre_tienda || 'Sin tienda' }}</td>

          <td class="text-[14px] p-2">{{ cliente.aplicacion }}</td>
          <td class="text-[14px] p-2">{{ cliente.membresia }}</td>
          <td class="text-[14px] p-2">
            {{ cliente.precio || 'Sin precio' }}
          </td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoTokenClass(cliente.estado_token)">
              {{ cliente.estado_token || 'Sin estado' }}
            </span>
          </td>

          <td class="text-[14px] p-2">{{ formatFecha(cliente.fecha_creacion) }}</td>
          <td>
            <div class="flex gap-2 items-center">
              <button @click="confirmDelete(cliente.id)"
                class="material-symbols-rounded hover:text-essentials-primary cursor-pointer">
                delete
              </button>
              <button @click="openDetalle(cliente.id)"
                class="material-symbols-rounded hover:text-blue-400 cursor-pointer">
                eye_tracking
              </button>
            </div>
          </td>
        </tr>

      </transition-group>
    </table>

    <div v-if="filteredClientes.length === 0" class="text-center mt-10 font-bold text-2xl text-gray-500">
      No hay coincidencias.
    </div>

    <ModalDetalles :isOpen="showModal" titulo="Datos del usuario:" descripcion="Detalles completos del cliente"
      @close="closeModal">
      <div v-if="detalleCliente">
        <div>
          <p class="text-4xl">Tabla de clientes taurus</p>
          <p>ID: {{ detalleCliente.id }}</p>
          <p>Estado: {{ detalleCliente.estado?.tipo_estado }} </p>
          <p><strong>Rol:</strong> {{ detalleCliente.rol?.tipo_rol || 'Sin rol' }}</p>
          <p>Tienda asignada: {{ detalleCliente.tienda?.nombre_tienda }}</p>
          <h1>Bienvenido, {{ detalleCliente.nombres_ct }} {{ detalleCliente.apellidos_ct }}</h1>
          <p><strong>Tipo de documento:</strong> {{ detalleCliente.tipo_documento?.documento_legal || 'Sin tipo de documento' }}</p>
          <p><strong>Documento:</strong> {{ detalleCliente.numero_documento_ct }}</p>
          <p><strong>Email:</strong> {{ detalleCliente.email_ct }}</p>
          <p><strong>Teléfono:</strong> {{ detalleCliente.telefono_ct }}</p>
          <p><strong>Fecha de creación:</strong> {{ formatFecha(detalleCliente.fecha_creacion) }}</p>
          <p><strong>Fecha de modificación:</strong> {{ formatFecha(detalleCliente.fecha_modificacion) }}</p>

          <br>

          <p class="text-4xl">Tabla de tienda sistematizada:</p>
          <h2>Datos de la tienda vinculada:</h2>
          <p>ID: {{ detalleCliente.tienda?.id }}</p>
          <p><strong>Estado de la tienda:</strong> {{ detalleCliente.tienda?.estado?.tipo_estado || 'Sin estado' }}</p>
          <p>Token: {{ detalleCliente.tienda?.token?.token_activacion }}</p>
          <p><strong>Nombre de la tienda:</strong> {{ detalleCliente.tienda?.nombre_tienda || 'Sin tienda' }}</p>
          <p><strong>Dirección:</strong> {{ detalleCliente.tienda?.direccion_ct || 'Sin dirección' }}</p>
          <p><strong>Ciudad:</strong> {{ detalleCliente.tienda?.barrio_ct || 'Sin ciudad' }}</p>
          <p><strong>Email de la tienda:</strong> {{ detalleCliente.tienda?.email_tienda || 'Sin email' }}</p>
          <p><strong>Teléfono:</strong> {{ detalleCliente.tienda?.telefono_ct }}</p>
          <p><strong>Fecha de creación:</strong> {{ formatFecha(detalleCliente.tienda?.fecha_creacion) }}</p>
          <p><strong>Fecha de modificación:</strong> {{ formatFecha(detalleCliente.tienda?.fecha_modificacion) }}</p>

          <br>


          <br>

      
          <p class="text-4xl">Tabla de detalles del plan</p>
          <h2>Detalles del Plan:</h2>
          <p>ID: {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.id }}</p>
          <p><strong>Sucursales:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_sucursales || 0
            }}</p>
          <p><strong>Empleados:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_empleados || 0
            }}</p>
          <p><strong>Proveedores:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_proveedores ||
            0 }}</p>
          <p><strong>Facturación electrónica:</strong> {{
            detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_electronica || 0 }}</p>
          <p><strong>Facturación física:</strong> {{
            detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_fisica || 0
            }}</p>
          <p><strong>Productos:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_productos || 0
            }}</p>
          <p><strong>Servicios:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_servicios || 0
            }}</p>
          <p><strong>Manejo reservas:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_reservas ||
            'No aplica' }}
          </p>
          <p><strong>Manejo POS:</strong> {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_pos || 'No aplica' }}</p>
          <p><strong>Manejo control de gastos:</strong> {{
            detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_control_gastos ||
            'No aplica' }}</p>
          <p><strong>Fecha de creación:</strong> {{
            formatFecha(detalleCliente.tienda?.aplicacion?.plan?.detalles?.fecha_creacion) }}</p>
          <p><strong>Fecha de modificación:</strong> {{
            formatFecha(detalleCliente.tienda?.aplicacion?.plan?.detalles?.fecha_modificacion) }}</p>
          <br>

        
          <p class="text-4xl">Tabla de datos de la membresia</p>
          <h2>Datos de la membresía:</h2>
          <p><strong>ID de la membresía:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.id || 'Sin membresía'
            }}</p>
          <p><strong>Nombre de la membresía:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.nombre_membresia
            || 'Sin membresía' }}</p>
          <p><strong>Precio:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.precio || 'Sin precio' }} USD</p>
          <p><strong>Periodo:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.periodo || 'Sin periodo' }}</p>
          <p><strong>Duración:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.duracion || 'Sin duración' }}
            días</p>
          <p><strong>Estado:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.estado?.tipo_estado || 'Sin estado' }}</p>
          <p><strong>Descripcion:</strong> {{ detalleCliente.tienda?.aplicacion?.membresia?.descripcion || 'Sin descripcion' }}.</p>

          <br>

          <p class="text-4xl">Tabla de datos del token</p>
          <h2>Datos del token:</h2>
          <p><strong>ID:</strong> {{ detalleCliente.tienda?.token?.id || 'Sin id' }}</p>
          <p><strong>estado de token:</strong> {{ detalleCliente.tienda?.token?.estado?.tipo_estado || 'Sin estado' }}
          </p>
          <p><strong>Tienda asignada:</strong> {{ detalleCliente.tienda?.nombre_tienda || 'Sin tienda' }}</p>
          <p><strong>Token activacion:</strong> {{ detalleCliente.tienda?.token?.token_activacion || 'Sin token' }}</p>
          <p><strong>Fecha de creación:</strong> {{ formatFecha(detalleCliente.tienda?.token?.fecha_creacion) }}
          </p>
          <p><strong>Fecha de modificación:</strong> {{
            formatFecha(detalleCliente.tienda?.token?.fecha_modificacion) }}</p>
          <br>
          <br>
          <button @click="logout">Cerrar sesión</button>
        </div>
      </div>
    </ModalDetalles>
  </div>
</template>
