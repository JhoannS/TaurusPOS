<script setup>
import { router, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import 'dayjs/locale/es';
import dayjs from 'dayjs';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';


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
  console.log("Flash messages:", usePage().props.flash); // Verificar si llega el mensaje

  if (usePage().props.flash && usePage().props.flash.success) {
    mostrarMensaje(usePage().props.flash.success, 'success');
  } else if (usePage().props.flash.error) {
    mostrarMensaje(usePage().props.flash.error, 'error');
  }
});


const auth = usePage().props.auth;


dayjs.locale('es')
const formatFecha = (fecha) => {
  if (!fecha) return 'Sin fecha'
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')
}



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

<Head title="Editar cliente" />
  <SaludoOpciones :auth="auth" />
  <main class="flex justify-between w-full h-[85vh] gap-4 py-3 px-6">
    <div class="righ w-full rounded-md">

      <!-- navegable -->
      <!-- navegable -->
      <div class="options flex gap-1 items-center text-[14px] mt-4">
        <a class="hover:text-essentials-secundary">
          <p>Dashboard</p>
        </a>
        <span class="material-symbols-rounded text-[18px]">chevron_right</span>
        <a class="hover:text-essentials-secundary">
          <p>Clientes</p>
        </a>
        <span class="material-symbols-rounded text-[18px]">chevron_right</span>
        <p class="font-bold">Editar cliente</p>
      </div>

      <h1 class="text-[25px] my-3">Editar cliente:</h1>
    </div>

    <div class="p-4">
    <h2 class="text-xl font-bold mb-4">Editar Cliente: {{ cliente.nombre_completo }}</h2>

    <form @submit.prevent="submit">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Nombres</label>
          <input v-model="form.nombres_ct" type="text" class="input" />
        </div>

        <div>
          <label>Apellidos</label>
          <input v-model="form.apellidos_ct" type="text" class="input" />
        </div>

        <div>
          <label>Teléfono</label>
          <input v-model="form.telefono" type="text" class="input" />
        </div>

        <div>
          <label>Tienda</label>
          <select v-model="form.id_tienda" class="input">
            <option v-for="tienda in tiendas" :key="tienda.id" :value="tienda.id">{{ tienda.nombre_tienda }}</option>
          </select>
        </div>

        <div>
          <label>Estado</label>
          <select v-model="form.id_estado" class="input">
            <option v-for="estado in estados" :key="estado.id" :value="estado.id">{{ estado.tipo_estado }}</option>
          </select>
        </div>

        <div>
          <label>Membresía</label>
          <select v-model="form.id_membresia" class="input">
            <option v-for="membresia in membresias" :key="membresia.id" :value="membresia.id">{{ membresia.nombre_membresia }}</option>
          </select>
        </div>
      </div>

      <button type="submit" class="mt-4 btn-primary">Guardar cambios</button>
    </form>
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
      <div class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-rojo">
      </div>
    </div>
  </main>

</template>
