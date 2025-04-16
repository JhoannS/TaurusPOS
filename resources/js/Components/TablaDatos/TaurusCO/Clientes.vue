<script setup>
import ModalDetalles from '@/Components/Modales/ModalDetallesClientes.vue';
import { ref, computed, onMounted } from 'vue';
import { router,usePage } from '@inertiajs/vue3';
import axios from 'axios';
import dayjs from 'dayjs';
import empty from '@images/empty.svg';
import 'dayjs/locale/es';
import { route } from 'ziggy-js';


const showModalEditar = ref(false);

function openEditModal() {
  // Abre el modal de edición usando los datos ya cargados en detalleCliente
  showModalEditar.value = true;
}

function closeEditModal() {
  showModalEditar.value = false;
}

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
  },
  success: String,
  montoApagar: Number,
})

const page = usePage()
const aplicacion = page.props.aplicacion
const rol = props.rol.tipo_rol  

const goToEditarCliente = (id) => {
  const nombreRol = props.rol.tipo_rol // 👈 Aquí obtienes el nombre del rol
  const app = props.aplicacion // 👈 nombre de la app como "TaurusCO"

  router.visit(route('aplicacion.editarClienteTaurus.id', { aplicacion: app, rol: nombreRol, id }))


}

function getEstadoClass(estado) {
  if (estado === 'Inactivo' || estado === 'Pendiente') return 'bg-semaforo-rojo';
  if (estado === 'Suspendido') return 'bg-semaforo-amarillo';
  if (estado === 'Activo' || estado === 'Pagada') return 'bg-semaforo-verde';
  return '';
}

function getEstadoTokenClass(token) {
  if (token === 'Inactivo' || token === 'Sin estado') return 'bg-semaforo-rojo';
  if (token === 'Suspendido') return 'bg-semaforo-amarillo';
  if (token === 'Activo') return 'bg-semaforo-verde';
  return '';
}


const listaClientes = ref([...props.clientes])

const filteredClientes = computed(() => {
  if (!listaClientes.value || !Array.isArray(listaClientes.value)) return [];
  if (!props.searchQuery.trim()) return listaClientes.value;

  const query = props.searchQuery.toLowerCase().trim();

  return listaClientes.value.filter(cliente => {
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




const mensajeNotificacion = ref('')
const tipoNotificacion = ref(null)
const mostrarNotificacion = ref(false)

onMounted(() => {
  // Verificar si se pasó un mensaje de éxito en las props
  if (props.success) {
    mostrarMensaje(props.success, 'success')
  }
})

const mostrarMensaje = (mensaje, tipo) => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true

  setTimeout(() => {
    mostrarNotificacion.value = false
  }, 5000)
}

const confirmDelete = (id) => {
  // Verificar si se intenta eliminar la propia cuenta
  if (id === props.userId) {
    // Mostrar notificación de error si se intenta eliminar la propia cuenta
    mostrarMensaje('No puedes eliminar tu propia cuenta.', 'error')
    return
  }

  if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
    deleteCliente(id)
  }
}

const deleteCliente = (id) => {
  if (!props.aplicacion || !props.rol) {
    console.error('Faltan parámetros de aplicación o rol');
    return;
  }

  router.delete(route('clientes.destroy', {
    aplicacion: props.aplicacion,
    rol: props.rol.id,
    id: id
  }), {
    onSuccess: () => {
      props.clientes = props.clientes.filter(cliente => cliente.id !== id);

      mostrarMensaje('Cliente eliminado con éxito', 'success');
      setTimeout(() => {
        location.reload();
      }, 3000);

    },
    onError: (error) => {
      mostrarMensaje('Error al eliminar el cliente', 'error');
    }
  });
};


function handleBeforeEnter(el) {
  el.classList.add('animate-fadeIn');
}
function handleBeforeLeave(el) {
  el.classList.add('animate-fadeOut');
}

const inicialesNombreCliente = computed(() => {
  if (!detalleCliente.value) return '';

  const nombres = detalleCliente.value.nombres_ct || '';
  const apellidos = detalleCliente.value.apellidos_ct || '';

  const firstNameInitial = nombres.split(' ')[0]?.charAt(0).toUpperCase() || '';
  const lastNameInitial = apellidos.split(' ')[0]?.charAt(0).toUpperCase() || '';

  return firstNameInitial + lastNameInitial;
});


const inicialesNombreTienda = computed(() => {
  if (!detalleCliente.value) return '';

  const nombreTienda = detalleCliente.value.tienda?.nombre_tienda || '';

  const inicialTienda = nombreTienda.split(' ')[0]?.charAt(0).toUpperCase() || '';
  return inicialTienda;
});


const inicialesNombrePlan = computed(() => {
  if (!detalleCliente.value) return '';

  const nombrePlan = detalleCliente.value.tienda?.aplicacion?.nombre_app || '';

  const inicialPlan = nombrePlan.split(' ')[0]?.charAt(0).toUpperCase() || '';
  return inicialPlan;
});

const formatCOP = (value) => {
  if (value === null || value === undefined || isNaN(value)) {
    return 'Sin precio';
  }
  return parseFloat(value).toLocaleString('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  });
};


const coloresBg = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
  'Essentials': 'bg-essentials-primary shadow-essentials',
  'Machine': 'bg-machine-primary shadow-machine',
  'Shopper': 'bg-shopper-primary shadow-shopper',
  'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
  'default': 'bg-gray-300 shadow-gray-300'
};

const coloresTexto = {
  'TaurusCO': 'text-universal-naranja',
  'Essentials': 'text-essentials-primary',
  'Machine': 'text-machine-primary',
  'Shopper': 'text-shopper-primary',
  'Smart': 'text-smart-primary',
  'default': 'text-gray-500'
};

const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgClase = computed(() => coloresBg[appName.value]);
const textoClase = computed(() => coloresTexto[appName.value]);
const gotaClase = computed(() => coloresBg[appName.value]);



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
          <th class="p-2 text-left">Monto a pagar</th>
          <th class="p-2 text-left">Fecha pago</th>
          <th class="p-2 text-left">Estado pago</th>
          <th class="p-2 text-left">Token</th>
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
          <td>{{ formatCOP(cliente.precio) || 'Sin pago' }}</td>
          <td class="text-[14px] p-2">{{ formatFecha(cliente.fecha_pago) }}</td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoClass(cliente.estado_pago)">
              {{ cliente.estado_pago || 'Sin pago' }}
            </span>
          </td>
          <td class="text-[14px] p-2">
            <span class="p-1 rounded-[5px] font-bold" :class="getEstadoTokenClass(cliente.estado_token)">
              {{ cliente.estado_token || 'Sin estado' }}
            </span>
          </td>

        
          <td>
            <div class="flex gap-2 items-center">
              <button @click="confirmDelete(cliente.id)"
                class="material-symbols-rounded hover:text-essentials-primary cursor-pointer">
                delete
              </button>
              <button  @click="goToEditarCliente(cliente.id)"
                class="material-symbols-rounded text-[18px] hover:text-semaforo-amarillo cursor-pointer">
                ink_marker
              </button>
              <button @click="openDetalle(cliente.id)"
                class="material-symbols-rounded hover:text-blue-400 cursor-pointer">
                info
              </button>
              
              
            </div>
          </td>
        </tr>

      </transition-group>
    </table>

    <div v-if="filteredClientes.length === 0" class="text-center mt-10 font-bold text-2xl text-gray-500">
      <div class="w-full flex items-center justify-center">
        <img :src="empty" alt="Logo" class=" w-[25%] my-2" />
      </div>
      No hay coincidencias.

    </div>

    <ModalDetalles :isOpen="showModal" @close="closeModal">
      <div v-if="detalleCliente">
        <div class="w-full flex justify-between items-center">

          <div class="usuario w-[30%]">
            <p>Detalles de cliente:</p>

            <div class="encabezado flex gap-3 items-center w-full mb-2">
              <div class="gota w-[8%]">
                <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                  :class="[gotaClase]">
                  <span class="text-white text-md font-bold">
                    {{ inicialesNombreCliente }}
                  </span>
                </div>
              </div>

              <div class="w-[92%]">
                <div class="flex w-full items-center justify-between mb-2">
                  <p class="text-[12px]">{{ detalleCliente.id }}</p>
                  <p class="flex items-center gap-1 text-[12px]"><span class="material-symbols-rounded text-[20px]"
                      :class='[textoClase]'>history</span>{{ formatFecha(detalleCliente.fecha_modificacion) }}</p>
                </div>
                <div class="nombre-Rol w-full flex justify-between items-center">
                  <div class="nombre">
                    <h3 class="font-semibold text-[20px] -mt-2">{{ detalleCliente.nombres_ct }} {{
                      detalleCliente.apellidos_ct }}</h3>
                    <p class="text-secundary-light text-sm -mt-2"> {{ detalleCliente.rol?.tipo_rol || 'Sin rol' }}</p>
                  </div>
                  <div class="estadoCliente flex items-center gap-2 ">
                    <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                      :class="getEstadoClass(detalleCliente.estado?.tipo_estado)"></div>
                    <p>{{ detalleCliente.estado?.tipo_estado }} </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="detallesCuenta my-2">
              <p class="text-secundary-light font-semibold">Datos personales:</p>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>id_card</span> {{ detalleCliente.tipo_documento?.documento_legal || 'Sin tipo de documento' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>tag</span> {{ detalleCliente.numero_documento_ct }}</p>
              </div>

              <div class="email-telefono flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>email</span> {{ detalleCliente.email_ct }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>phone</span> {{ detalleCliente.telefono_ct }}</p>
              </div>
            </div>


            <div class="detallesCuenta my-2">
              <p class="text-secundary-light font-semibold">Detalles membresía:</p>
              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>card_membership</span> {{
                      detalleCliente.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>timer</span>{{ detalleCliente.tienda?.aplicacion?.membresia?.duracion || 'Pago pendiente' }} días</p>
              </div>

              <div class=" flex justify-end items-center">
               
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>timer</span>{{ detalleCliente.tienda?.aplicacion?.dias_restantes || 'Sin duración' }} días restantes</p>
              </div>

              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>format_italic</span> {{
                      detalleCliente.tienda?.aplicacion?.membresia?.descripcion || 'Sin descripcion' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>history_toggle_off</span> {{
                      detalleCliente.tienda?.aplicacion?.membresia?.periodo || 'Sin periodo' }} </p>
              </div>

              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>attach_money</span>{{
                      formatCOP(detalleCliente.tienda?.aplicacion?.membresia?.precio) || 'Sin precio' }} COP</p>
                <div class="estadoCliente flex items-center gap-2 ">
                  <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                    :class="getEstadoClass(detalleCliente.tienda?.aplicacion?.membresia?.estado?.tipo_estado)"></div>
                  <p> {{ detalleCliente.tienda?.aplicacion?.membresia?.estado?.tipo_estado || 'Sin estado' }} </p>
                </div>
              </div>
            </div>

          </div>

          <div class="tienda w-[30%]">
            <p>Detalles de tienda:</p>
            <div class="encabezado flex gap-3 items-center w-full mb-2">
              <div class="gota w-[8%]">
                <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                  :class="[gotaClase]">
                  <span class="text-white text-md font-bold">
                    {{ inicialesNombreTienda }}
                  </span>
                </div>
              </div>

              <div class="w-[92%]">
                <div class="flex w-full items-center justify-between mb-2">
                  <p class="text-[12px]">{{ detalleCliente.tienda?.id }}</p>
                  <p class="flex items-center gap-1 text-[12px]"><span class="material-symbols-rounded text-[20px]"
                      :class='[textoClase]'>history</span>{{ formatFecha(detalleCliente.tienda?.fecha_modificacion) }}
                  </p>
                </div>
                <div class="nombre-Rol w-full flex justify-between items-center">
                  <div class="nombre">
                    <h3 class="font-semibold text-[20px] -mt-2">{{ detalleCliente.tienda?.nombre_tienda || 'Sin tienda'
                      }}</h3>
                    <p class="text-secundary-light text-sm -mt-2">{{ detalleCliente.tienda?.direccion_tienda || 'Sin dirección' }} - {{ detalleCliente.tienda?.barrio_tienda || 'Sin ciudad' }}</p>
                  </div>
                  <div class="estadoCliente flex items-center gap-2 ">
                    <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                      :class="getEstadoClass(detalleCliente.tienda?.estado?.tipo_estado)"></div>
                    <p>{{ detalleCliente.tienda?.estado?.tipo_estado || 'Sin estado' }}</p>
                  </div>
                </div>
              </div>
            </div>


            <div class="my-2">
              <p class="text-secundary-light font-semibold">Datos del establecimiento:</p>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>verified</span> {{ detalleCliente.tienda?.aplicacion?.nombre_app || 'Sin app'
                  }}
                  App</p>
              </div>
              <div class="email-telefono flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>email</span> {{ detalleCliente.tienda?.email_tienda || 'Sin email' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>phone</span>{{ detalleCliente.tienda?.telefono_tienda }}</p>
              </div>
            </div>

            <div class="my-2">
              <p class="text-secundary-light font-semibold">Detalles del token:</p>
              <div class="token">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>key</span> {{ detalleCliente.tienda?.token?.token_activacion }}</p>
              </div>
              <div class="email-telefono flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>timer</span> {{ formatFecha(detalleCliente.tienda?.token?.fecha_modificacion)
                  }}</p>
                <div class="estadoCliente flex items-center gap-2 ">
                  <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                    :class="getEstadoClass(detalleCliente.tienda?.token?.estado?.tipo_estado)"></div>
                  <p>{{ detalleCliente.tienda?.token?.estado?.tipo_estado || 'Sin estado' }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="plan w-[30%]">
            <p>Detalles de aplicación:</p>
            <div class="encabezado flex gap-3 items-center w-full mb-2">
              <div class="gota w-[8%]">
                <div class="user h-[40px] w-[40px] rounded-full overflow-hidden flex items-center justify-center"
                  :class="[gotaClase]">
                  <span class="text-white text-md font-bold">
                    {{ inicialesNombrePlan }}
                  </span>
                </div>
              </div>

              <div class="w-[92%]">
                <div class="flex w-full items-center justify-between mb-2">
                  <p class="text-[12px]">{{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.id }}</p>
                  <p class="flex items-center gap-1 text-[12px]"><span class="material-symbols-rounded text-[20px]"
                      :class='[textoClase]'>history</span>
                    {{ formatFecha(detalleCliente.tienda?.aplicacion?.plan?.detalles?.fecha_modificacion) }}</p>
                </div>
                <div class="nombre-Rol w-full flex justify-between items-center">
                  <div class="nombre">
                    <h3 class="font-semibold text-[20px] -mt-2">{{ detalleCliente.tienda?.aplicacion?.nombre_app || 'Sin app' }} App</h3>
                    <p class="text-secundary-light text-sm -mt-2">{{
                      detalleCliente.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }}</p>
                  </div>
                  <div class="estadoCliente flex items-center gap-2 ">
                    <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                      :class="getEstadoClass(detalleCliente.tienda?.aplicacion?.membresia?.estado?.tipo_estado)"></div>
                    <p> {{ detalleCliente.tienda?.aplicacion?.membresia?.estado?.tipo_estado || 'Sin estado' }} </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="my-2">
              <p class="text-secundary-light font-semibold">Detalles del plan:</p>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>swap_horiz</span> Sucursales virtuales: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_sucursales || 0}}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>identity_platform</span> Empleados: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_empleados || 0
                  }}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>identity_platform</span> Proveedores: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_proveedores || 0 }}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>category</span> Productos: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_productos || 0}}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>category</span> Servicios: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_servicios || 0}}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>receipt</span> Facturación electrónica:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_electronica || 0 }}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>picture_As_pdf</span> Facturación física:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_fisica || 0 }}</p>
                <p>Usadas: 0</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>event</span> Manejo reservas: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_reservas || 'No aplica' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>savings</span> Manejo POS: {{
                      detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_pos || 'No aplica' }}</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>verified</span> Contabilidad:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_contabilidad || 'No aplica' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>monitoring</span> Manejo gastos:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_control_gastos || 'No aplica' }}</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>barcode_scanner</span> Etiquetado barras:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_etiquetado_barras || 'No aplica' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>qr_code_2</span> Códigos Qr:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_codigos_qr || 'No aplica' }}</p>
              </div>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>diversity_1</span> Orden trabajo:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_orden_trabajo || 'No aplica' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textoClase]'>theater_comedy</span> Encuestas:
                  {{ detalleCliente.tienda?.aplicacion?.plan?.detalles?.manejo_encuestas || 'No aplica' }}</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </ModalDetalles>


    <!-- Notificación de éxito -->
    <div v-if="mostrarNotificacion && tipoNotificacion === 'success'"
      class="notificacion translate-y-8 absolute w-[max-content] left-0 right-0 top-6 ml-auto mr-auto rounded-md bg-semaforo-verde_opacity text-mono-blanco shadow-semaforo-verde">
      <div class="notificacion_body flex justify-center gap-3 items-center py-3 px-2">
        <div class="flex gap-2 items-center">
          <i class="material-symbols-rounded text-semaforo-verde">check_circle</i>
          <p>{{ mensajeNotificacion }}</p>
        </div>
      </div>
      <div
        class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-verde">
      </div>
    </div>

    <!-- Notificación de error -->
    <div v-if="mostrarNotificacion && tipoNotificacion === 'error'"
      class="notificacion translate-y-8 absolute w-[max-content] left-0 right-0 top-6 ml-auto mr-auto rounded-md bg-semaforo-rojo_opacity text-mono-blanco shadow-semaforo-verde">
      <div class="notificacion_body flex justify-center gap-3 items-center py-3 px-2">
        <div class="flex gap-2 items-center">
          <i class="material-symbols-rounded text-semaforo-rojo">cancel</i>
          <p>{{ mensajeNotificacion }}</p>
        </div>
      </div>
      <div class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-rojo">
      </div>
    </div>
  </div>
</template>
