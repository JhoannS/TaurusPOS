<script setup>
import { router, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';
import 'dayjs/locale/es';
import dayjs from 'dayjs';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
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

const user = props.auth.user
const auth = usePage().props.auth;
const clientes = ref(props.clientes);

dayjs.locale('es')
const formatFecha = (fecha) => {
  if (!fecha) return 'Sin fecha'
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')
}

const clientesPorActivacion = ref([])
const cargarClientesPorActivacion = async () => {
  try {
    const { data } = await axios.get(route('clientes.activacion', {
      aplicacion: props.aplicacion,
      rol: props.auth.user.rol
    }))
    clientesPorActivacion.value = data
  } catch (error) {
    console.error('Error al cargar clientes:', error)
  }
}

onMounted(() => {
  cargarClientesPorActivacion()
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

const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgClase = computed(() => coloresBg[appName.value]);
const textoClase = computed(() => coloresTexto[appName.value]);
const searchQuery = ref('');



const logout = () => {
  router.post(route('logout'))
}

</script>

<template>

  <Head :title="`Tu App ${user.tienda?.aplicacion?.nombre_app || 'Dashboard'}`" />
  <SaludoOpciones :auth="auth" />
  <main class="flex justify-between w-full h-[85vh] gap-4 py-3 px-6">
    <div class="righ w-full rounded-md">

      <!-- navegable -->
      <div class="options flex gap-1 items-center text-[14px]">
        <p class="font-bold flex items-center gap-1"><span class="material-symbols-rounded">home</span> Inicio</p>
      </div>
      <h1 class="text-[25px] my-3">Dashboard:</h1>
      <div class="cards flex justify-between gap-3">
        <div
          class="border  bg-secundary-opacity border-secundary-light rounded-md w-[40%] p-2 flex justify-between items-center">
          <div class="metodoPago-monto">
            <p class="text-[14px]">Dinero activo:</p>
            <p class="font-bold text-[18px]">  $</p>
          </div>
          <div class="contador p-2 rounded-md flex justify-center items-center w-10 h-10 font-bold" :class="[bgClase]">
            <span class="material-symbols-rounded">
              attach_money
            </span>
          </div>
        </div>

        <div
          class="border  bg-secundary-opacity border-secundary-light rounded-md w-full p-2 flex justify-between items-center">
          <div class="clientesInactivos">
            <p class="text-[14px]">Clientes por activación:</p>


            <div v-if="clientesPorActivacion.length === 0" class="text-semaforo-verde font-semibold">
              Estás al día, muy bien 👌
            </div>


            <div v-else>
              <div v-for="cliente in clientesPorActivacion" :key="cliente.id"
                class="clientesActivacion flex justify-between items-center gap-4 w-full">
                <div class="flex items-center gap-2">
                  <div class="gota bg-semaforo-rojo h-3 w-5 rounded-full"></div>
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
      </div>
      <ExportarExcel idTabla="tabla" nombreArchivo="Movimientos_Almacenados" titulo="Movimientos almacenados" />
      <!-- formulario -->
      <Clientes :auth="auth" :user-id="auth.user.id"
        :rol="typeof auth.user.rol === 'object' ? auth.user.rol : { id: auth.user.rol }" :aplicacion="aplicacion"
        :clientes="clientes" :searchQuery="searchQuery" />


    </div>
    
  </main>
  
</template>
