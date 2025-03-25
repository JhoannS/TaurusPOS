<template>

  <Head title="Dashboard" />
  <div class="header flex items-center p-3 gap-3">
    <div class="left p-3  bg-secundary-opacity border border-secundary-light w-[20%] rounded-md">
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
    <header class="flex items-center border border-l-secundary-light bg-secundary-opacity p-3 rounded-md w-[80%]">
      <SaludoOpciones :auth="auth" />
    </header>
  </div>
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
          class="border  bg-secundary-opacity border-secundary-light rounded-md w-full p-2 flex justify-between items-center">
          <!-- <div class="clientesInactivos">
            <p class="text-[14px]">Clientes por activación:</p>
            <div class="clientesActivacion flex justify-between items-center gap-4 w-full">
              <div v-for="cliente in clientesInactivos" :key="cliente.nombres_ct" class="flex items-center gap-2">
                <div class="gota bg-semaforo-rojo h-3 w-5 rounded-full"></div>
                <div>
                  <h3 class="font-semibold">{{ cliente.nombres_ct }}</h3>
                  <p class="-mt-[5px] text-secundary-light text-[13px] font-medium">
                    {{ cliente.nombre_tienda || 'Sin tienda' }}
                  </p>
                </div>
              </div>
            </div>
          </div> -->
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
      <Clientes :auth="auth" :user-id="auth.user.id"
        :rol="typeof auth.user.rol === 'object' ? auth.user.rol : { id: auth.user.rol }" :aplicacion="aplicacion"
        :clientes="clientes" :searchQuery="searchQuery" />


    </div>


  </main>

  <div>
    <p class="text-4xl">Tabla de clientes taurus</p>
    <p>ID: {{ user.id }}</p>
    <p>Estado: {{ user.estado?.tipo_estado }} </p>
    <p><strong>Rol:</strong> {{ user.rol?.tipo_rol || 'Sin rol' }}</p>
    <p>Tienda asignada: {{ user.tienda?.nombre_tienda }}</p>
    <h1>Bienvenido, {{ user.nombres_ct }} {{ user.apellidos_ct }}</h1>
    <p><strong>Tipo de documento:</strong> {{ user.tipo_documento?.documento_legal || 'Sin tipo de documento' }}</p>
    <p><strong>Documento:</strong> {{ user.numero_documento_ct }}</p>
    <p><strong>Email:</strong> {{ user.email_ct }}</p>
    <p><strong>Teléfono:</strong> {{ user.telefono_ct }}</p>
    <p><strong>Fecha de creación:</strong> {{ formatFecha(user.fecha_creacion) }}</p>
    <p><strong>Fecha de modificación:</strong> {{ formatFecha(user.fecha_modificacion) }}</p>

    <br>

    <p class="text-4xl">Tabla de tienda sistematizada:</p>
    <h2>Datos de la tienda vinculada:</h2>
    <p>ID: {{ user.tienda?.id }}</p>
    <p><strong>Estado de la tienda:</strong> {{ user.tienda?.estado?.tipo_estado || 'Sin estado' }}</p>
    <p>Token: {{ user.tienda?.token?.token_activacion }}</p>
    <p><strong>Nombre de la tienda:</strong> {{ user.tienda?.nombre_tienda || 'Sin tienda' }}</p>
    <p><strong>Dirección:</strong> {{ user.tienda?.direccion_ct || 'Sin dirección' }}</p>
    <p><strong>Ciudad:</strong> {{ user.tienda?.barrio_ct || 'Sin ciudad' }}</p>
    <p><strong>Email de la tienda:</strong> {{ user.tienda?.email_tienda || 'Sin email' }}</p>
    <p><strong>Teléfono:</strong> {{ user.tienda?.telefono_ct }}</p>
    <p><strong>Fecha de creación:</strong> {{ formatFecha(user.tienda?.fecha_creacion) }}</p>
    <p><strong>Fecha de modificación:</strong> {{ formatFecha(user.tienda?.fecha_modificacion) }}</p>

    <br>


    <br>

    <!-- ✅ Datos del plan -->
    <p class="text-4xl">Tabla de detalles del plan</p>
    <h2>Detalles del Plan:</h2>
    <p>ID: {{ user.tienda?.aplicacion?.plan?.detalles?.id }}</p>
    <p><strong>Sucursales:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_sucursales || 0 }}</p>
    <p><strong>Empleados:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_empleados || 0 }}</p>
    <p><strong>Proveedores:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_proveedores || 0 }}</p>
    <p><strong>Facturación electrónica:</strong> {{
      user.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_electronica || 0 }}</p>
    <p><strong>Facturación física:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_fisica || 0
      }}</p>
    <p><strong>Productos:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_productos || 0 }}</p>
    <p><strong>Servicios:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_servicios || 0 }}</p>
    <p><strong>Manejo reservas:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_reservas || 'No aplica' }}
    </p>
    <p><strong>Manejo POS:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_pos || 'No aplica' }}</p>
    <p><strong>Manejo control de gastos:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_control_gastos ||
      'No aplica' }}</p>
    <p><strong>Fecha de creación:</strong> {{ formatFecha(user.tienda?.aplicacion?.plan?.detalles?.fecha_creacion) }}
    </p>
    <p><strong>Fecha de modificación:</strong> {{
      formatFecha(user.tienda?.aplicacion?.plan?.detalles?.fecha_modificacion) }}</p>
    <br>

    <!-- ✅ Datos de la membresía -->
    <p class="text-4xl">Tabla de datos de la membresia</p>
    <h2>Datos de la membresía:</h2>
    <p><strong>ID de la membresía:</strong> {{ user.tienda?.aplicacion?.membresia?.id || 'Sin membresía' }}</p>
    <p><strong>Nombre de la membresía:</strong> {{ user.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }}</p>
    <p><strong>Precio:</strong> {{ user.tienda?.aplicacion?.membresia?.precio || 'Sin precio' }} USD</p>
    <p><strong>Periodo:</strong> {{ user.tienda?.aplicacion?.membresia?.periodo || 'Sin periodo' }}</p>
    <p><strong>Duración:</strong> {{ user.tienda?.aplicacion?.membresia?.duracion || 'Sin duración' }} días</p>
    <p><strong>Estado:</strong> {{ user.tienda?.aplicacion?.membresia?.estado?.tipo_estado || 'Sin estado' }}</p>
    <p><strong>Descripcion:</strong> {{ user.tienda?.aplicacion?.membresia?.descripcion || 'Sin descripcion' }}.</p>

    <br>

    <!-- ✅ Datos de token -->
    <p class="text-4xl">Tabla de datos del token</p>
    <h2>Datos del token:</h2>
    <p><strong>ID:</strong> {{ user.tienda?.token?.id || 'Sin id' }}</p>
    <p><strong>estado de token:</strong> {{ user.tienda?.token?.estado?.tipo_estado || 'Sin estado' }}</p>
    <p><strong>Tienda asignada:</strong> {{ user.tienda?.nombre_tienda || 'Sin tienda' }}</p>
    <p><strong>Token activacion:</strong> {{ user.tienda?.token?.token_activacion || 'Sin token' }}</p>
    <p><strong>Fecha de creación:</strong> {{ formatFecha(user.tienda?.token?.fecha_creacion) }}
    </p>
    <p><strong>Fecha de modificación:</strong> {{
      formatFecha(user.tienda?.token?.fecha_modificacion) }}</p>
    <br>
    <br>

    <button @click="logout">Cerrar sesión</button>
  </div>


</template>

<script setup>
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Clientes from '@/Components/TablaDatos/TaurusCo/Clientes.vue';
import ExportarExcel from '@/components/ExportarExcel/ExportarExcel.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';

const showModal = ref(false);
const branchTitle = ref('');
const branchDescription = ref('');



import dayjs from 'dayjs'
import 'dayjs/locale/es' // ✅ Importa el idioma español

dayjs.locale('es')
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { router, Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
  auth: {
    type: Object,
    required: true
  },
  clientes: {
    type: Array,
    default: () => [],
  },
  aplicacion: { // ✅ Define la prop correctamente
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
