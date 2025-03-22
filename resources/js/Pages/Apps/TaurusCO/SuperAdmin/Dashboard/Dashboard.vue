<template>

  <Head title="Dashboard" />
  <main class="flex justify-between w-full min-h-screen gap-4 p-4">
    <div class="left p-3 bg-secundary-opacity border border-secundary-light w-[20%] rounded-md">
      <div class="infoTienda flex gap-2">
        <div class="gota h-10 w-10 rounded-full bg-universal-naranja"></div>
        <div class="logo">
          <div v-if="auth && auth.user">
            <h3 class="font-semibold"> {{ user.tienda?.nombre_tienda || 'Sin tienda' }}</h3>
            <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
              {{ user.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }} - {{
                user.tienda?.aplicacion?.membresia?.duracion || 'Sin duración' }} días
            </p>
          </div>
          <div v-else>
            <p>Cargando información del usuario...</p>
          </div>
        </div>
      </div>


    </div>
    <div class="righ w-[80%] rounded-md">
      <header class="flex items-center border border-l-secundary-light bg-secundary-opacity p-3 rounded-md mb-[20px]">
        <SaludoOpciones :auth="auth" />
      </header>

      <h1 class="text-[25px] my-3">Dashboard:</h1>
      <div class="cards flex justify-between gap-3">
        <div
          class="border  bg-secundary-opacity border-secundary-light rounded-md w-[40%] p-2 flex justify-between items-center">
          <div class="metodoPago-monto">
            <p class="text-[12px]">Son datos y hay que darlos</p>
            <p class="text-[14px]">Producto con menor Stock:</p>
            <p class="font-bold text-[18px]">Producto pronto a terminarse</p>
          </div>
          <div class="contador p-2 rounded-md flex justify-center items-center w-10 h-10 font-bold" :class="[bgClase]">
            <span class="material-symbols-rounded">
              category
            </span>
          </div>
        </div>

        <div
          class="border  bg-secundary-opacity border-secundary-light rounded-md w-[60%] p-2 flex justify-between items-center">
          <div class="metodoPago-monto">
            <p class="text-[14px]">Clientes por activacion:</p>
            <div class="clientesActivacion flex justify-between items-center gap-4 w-full">
              <div class="flex items-center gap-2">
                <div class="gota bg-semaforo-rojo h-3 w-5 rounded-full"></div>
                <div class="">
                  <div v-if="auth && auth.user">
                    <h3 class="font-semibold"> {{ auth.user.nombres_ct }}</h3>
                    <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                      {{ user.tienda?.nombre_tienda || 'Sin tienda' }}
                    </p>
                  </div>
                  <div v-else>
                    <p>Cargando información del usuario...</p>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <div class="gota bg-semaforo-rojo h-3 w-5 rounded-full"></div>
                <div class="">
                  <div v-if="auth && auth.user">
                    <h3 class="font-semibold"> {{ auth.user.nombres_ct }}</h3>
                    <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                      {{ user.tienda?.nombre_tienda || 'Sin tienda' }}
                    </p>
                  </div>
                  <div v-else>
                    <p>Cargando información del usuario...</p>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <div class="gota bg-semaforo-rojo h-3 w-5 rounded-full"></div>
                <div class="">
                  <div v-if="auth && auth.user">
                    <h3 class="font-semibold"> {{ auth.user.nombres_ct }}</h3>
                    <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                      {{ user.tienda?.nombre_tienda || 'Sin tienda' }}
                    </p>
                  </div>
                  <div v-else>
                    <p>Cargando información del usuario...</p>
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



        <a :href="route('essentials.admin.crearItem')">
          <button class="rounded-md p-1.5 flex items-center gap-2" :class="[bgClase]">Crear cliente<span
              class="material-symbols-rounded"> outbound </span></button>
        </a>



      </div>
      <ExportarExcel idTabla="tabla" nombreArchivo="Movimientos_Almacenados" titulo="Movimientos almacenados" />
      <!-- formulario -->
      <Clientes :clientes="clientes" :searchQuery="searchQuery" />
    </div>

  </main>

</template>

<script setup>
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Clientes from '@/Components/TablaDatos/TaurusCo/Clientes.vue';
import ExportarExcel from '@/components/ExportarExcel/ExportarExcel.vue';

import dayjs from 'dayjs'
import 'dayjs/locale/es' // ✅ Importa el idioma español

dayjs.locale('es')
import { ref, onMounted, onUnmounted, computed, } from 'vue';
import { router, Head } from '@inertiajs/vue3'

const props = defineProps({
  auth: {
    type: Object,
    required: true
  },
  clientes: {
    type: Array,
    default: () => [],
  },
})

const user = props.auth.user

const logout = () => {
  router.post(route('logout'))
}

const formatFecha = (fecha) => {
  if (!fecha) return 'Sin fecha'
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')
}


// ✅ Clases dinámicas según la aplicación
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

</script>
