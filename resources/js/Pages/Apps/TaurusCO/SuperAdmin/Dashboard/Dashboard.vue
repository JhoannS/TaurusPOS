<script setup>
import { router, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import axios from 'axios';
import 'dayjs/locale/es';
import dayjs from 'dayjs';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import Clientes from '@/Components/TablaDatos/TaurusCO/Clientes.vue';
import ExportarExcel from '@/Components/ExportarExcel/ExportarExcel.vue';

const props = defineProps({
  auth: {
    type: Object,
    required: true
  },
  clientes: {
    type: Array,
    default: () => [],
  },
  aplicacion: {
    type: String,
    default: ''
  },
  errors: {
    type: Object,
    required: true
  },
})

// Acceder a los datos de Inertia, incluyendo flash
const { flash } = usePage().props;

// Definir variables reactivas para notificaciones
const mensajeNotificacion = ref('');
const tipoNotificacion = ref(null);
const mostrarNotificacion = ref(false);

// Función para mostrar notificaciones
const mostrarMensaje = (mensaje, tipo) => {
  mensajeNotificacion.value = mensaje;
  tipoNotificacion.value = tipo;
  mostrarNotificacion.value = true;
  setTimeout(() => {
    mostrarNotificacion.value = false;
  }, 5000);
};

onMounted(() => {
  if (usePage().props.flash && usePage().props.flash.success) {
    mostrarMensaje(usePage().props.flash.success, 'success');
  } else if (usePage().props.flash.error) {
    mostrarMensaje(usePage().props.flash.error, 'error');
  }
});



const user = props.auth.user
const auth = usePage().props.auth;
const clientes = ref(props.clientes);

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


const clientesPorActivacion = ref([])
const clientesPrevios = ref([])
const animarClientes = ref(false)

const cargarClientesPorActivacion = async () => {
  try {
    const { data } = await axios.get(route('clientes.activacion', {
      aplicacion: props.aplicacion,
      rol: props.auth.user.rol
    }))

    // Comparar si hay cambios
    const nuevoHash = JSON.stringify(data)
    const previoHash = JSON.stringify(clientesPrevios.value)

    if (nuevoHash !== previoHash) {
      clientesPrevios.value = data
      clientesPorActivacion.value = data

      animarClientes.value = true
      setTimeout(() => (animarClientes.value = false), 1000)
    }
  } catch (error) {
    console.error('Error al cargar clientes:', error)
  }
}


const dineroActivo = ref(0)
const dineroAnterior = ref(0)
const animarCambio = ref(false)

const cargarDineroActivo = async () => {
  try {
    const { data } = await axios.get(route('dinero.activo', {
      aplicacion: props.aplicacion,
      rol: props.auth.user.rol
    }))

    if (data.total_activo !== dineroActivo.value) {
      dineroAnterior.value = dineroActivo.value
      dineroActivo.value = data.total_activo

      animarCambio.value = true
      setTimeout(() => animarCambio.value = false, 1000)
    }
  } catch (error) {
    console.error('Error al cargar dinero activo:', error)
  }
}

onMounted(() => {
  cargarDineroActivo()
  cargarClientesPorActivacion()

  //  setInterval(cargarDineroActivo, 5000)
  //  setInterval(cargarClientesPorActivacion, 5000)
})


const coloresBg = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
  'Essentials': 'bg-essentials-primary shadow-essentials',
  'Machine': 'bg-machine-primary shadow-machine',
  'Shopper': 'bg-shopper-primary shadow-shopper',
  'default': 'bg-gray-300 shadow-gray-300'
};
const coloresTexto = {
  'TaurusCO': 'text-universal-naranja',
  'Essentials': 'text-essentials-primary',
  'Machine': 'text-machine-primary',
  'Shopper': 'text-shopper-primary',
  'default': 'text-gray-500'
};

const button = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
  'Essentials': 'bg-essentials-primary shadow-essentials rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
  'Machine': 'bg-machine-primary shadow-machine rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
  'Shopper': 'bg-shopper-primary shadow-shopper rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
  'Smart': 'bg-smart-primary shadow-smart text-mono-negro rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
  'default': 'bg-gray-300 shadow-gray-300'
};

const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgClase = computed(() => coloresBg[appName.value]);
const buttonFocus = computed(() => button[appName.value]);
const textoClase = computed(() => coloresTexto[appName.value]);
const searchQuery = ref('');



const logout = () => {
  router.post(route('logout'))
}

</script>

<template>

  <Head :title="`Bienvenido ${user.nombres_ct || 'Dashboard'}`" />

  <div class="w-full h-[100%] px-[40px] py-[20px]">
    <SaludoOpciones :auth="auth" />

    <main class="flex justify-between w-full h-[80vh] gap-4 mt-3">
      <div class="righ w-full rounded-md">

        <!-- navegable -->
        <div class="options flex gap-1 items-center text-[14px]">
          <p class="font-bold flex items-center gap-1"><span class="material-symbols-rounded">home</span> Inicio</p>
        </div>
        <h1 class="text-[25px] my-3">Dashboard:</h1>
        <div class="cards flex justify-between gap-3">

          <div
            class="border bg-secundary-opacity border-secundary-light rounded-md w-[40%] p-2 flex justify-between items-center">
            <div class="metodoPago-monto">
              <p class="text-[14px]">Dinero activo:</p>
              <p :class="['font-bold text-[18px]',]">
                {{ formatCOP(dineroActivo) }}
              </p>
            </div>
            <div class="contador p-2 rounded-md flex justify-center items-center w-10 h-10 font-bold"
              :class="[bgClase]">
              <span class="material-symbols-rounded">attach_money</span>
            </div>
          </div>


          <div
            class="border  bg-secundary-opacity border-secundary-light rounded-md w-full p-2 flex justify-between items-center">
            <div class="clientesInactivos">
              <p class="text-[14px]">Clientes por activación:</p>

              <div v-if="clientesPorActivacion.length === 0" class="text-semaforo-verde font-semibold">
                Estás al día, muy bien 👌
              </div>

              <div v-else :class="{ 'animate-ping-texto': animarClientes }"
                class="transition-all duration-500 flex items-center gap-2">
                <div v-for="cliente in clientesPorActivacion" :key="cliente.id" class="clientesActivacion">
                  <div class="flex items-center gap-2 w-[150px]">
                    <div class="h-[12px] w-[20px] rounded-full" :class="[bgClase]"></div>
                    <div>
                      <h3 class="font-semibold">{{ cliente.nombres_ct }} {{ cliente.apellidos_ct }}</h3>
                      <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                        {{ cliente.nombre_tienda }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- header administrativo -->
        <div class="titulo-input-btn my-4 flex justify-between items-center gap-5">
          <h4 class="font-semibold text-[25px]">Gestion de tauranos</h4>
          <div class="input-buscador">
            <input v-model="searchQuery" type="search" placeholder="Buscar clientes..." class="" />
            <span class="material-symbols-rounded" :class='[textoClase]'>travel_explore</span>
          </div>
          <button class="" :class="[buttonFocus]">Agregar nuevo cliente<span class="material-symbols-rounded">
            outbound </span></button>

        </div>

        <ExportarExcel idTabla="tabla" nombreArchivo="Movimientos_Almacenados" titulo="Movimientos almacenados" />

       
        <!-- formulario -->
        <Clientes :auth="auth" :user-id="auth.user.id"
          :rol="typeof auth.user.rol === 'object' ? auth.user.rol : { id: auth.user.rol }" :aplicacion="aplicacion"
          :clientes="clientes" :searchQuery="searchQuery" />
      </div>
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
      <!-- ✅ Notificación de error -->
      <div v-if="mostrarNotificacion && tipoNotificacion === 'error'"
        class="notificacion translate-y-8 absolute w-[max-content] left-0 right-0 top-6 ml-auto mr-auto rounded-md bg-semaforo-rojo_opacity text-mono-blanco shadow-semaforo-verde">
        <div class="notificacion_body flex justify-center gap-3 items-center py-3 px-2">
          <div class="flex gap-2 items-center">
            <i class="material-symbols-rounded text-semaforo-rojo">cancel</i>
            <p>{{ mensajeNotificacion }}</p>
          </div>
        </div>
        <div
          class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-rojo">
        </div>
      </div>
    </main>
  </div>


</template>
