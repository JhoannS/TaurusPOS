<script setup>
import { router, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import 'dayjs/locale/es';
import { route } from 'ziggy-js';
import dayjs from 'dayjs';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';


const props = defineProps({
  auth: {
    type: Object,
    required: true
  },
  cliente: {
    type: Object,
    required: true
  },
  tiendas: {
    type: Array,
    required: true
  },
  membresias: {
    type: Array,
    required: true
  },
  estados: {
    type: Array,
    required: true
  },
  tiposDocumento: {
    type: Array,
    required: true,
  },
  aplicacionesAgrupadas: {
    type: Array,
    required: true,
  },
  roles: {
    type: Array,
    required: true
  },
  errors: {
    type: Object,
    required: true
  },
})

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

const dashboardRoute = computed(() => new URL(route('aplicacion.dashboard', { aplicacion, rol }), window.location.origin).pathname);

const form = ref({
  nombres_ct: props.cliente.nombres_ct || '',
  apellidos_ct: props.cliente.apellidos_ct || '',
  id_tipo_documento: props.cliente.id_tipo_documento ?? null,
  id_rol: props.cliente.id_rol ?? null,
  numero_documento_ct: props.cliente.numero_documento_ct || '',
  email_ct: props.cliente.email_ct || '',
  telefono_ct: props.cliente.telefono_ct || '',
  id_tienda: props.cliente.id_tienda ?? null,
  id_estado: props.cliente.id_estado ?? null,
  id_estado_token: props.cliente.id_estado_token ?? null, // ✅ correcto
  id_membresia: props.cliente.id_membresia ?? null,
  id_aplicacion_web: props.cliente.tienda?.id_aplicacion_web || 'Cargando...',

})


const validarFormulario = () => {
  if (!form.value.nombres_ct || !form.value.apellidos_ct || !form.value.numero_documento_ct) {
    mostrarMensaje('Los campos requeridos no pueden estar vacíos', 'error');
    return false;
  }
  return true;
};

const submitForm = () => {
  if (!validarFormulario()) return;

  router.put(
    route('aplicacion.editarClienteTaurus.actualizar', {
      aplicacion: appName.value,
      rol: props.auth.user.rol.tipo_rol, // Asegúrate que esta info esté disponible
      id: props.cliente.id
    }),
    form.value,
    {
      onFinish: () => {
        mostrarMensaje('Cliente actualizado correctamente', 'success');
      },
      onError: (error) => {
        mostrarMensaje(error, 'error');
      }
    }
  );
};

// Acceder a los datos de Inertia, incluyendo flash

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
  const { flash } = usePage().props;

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
const textClase = computed(() => coloresTexto[appName.value]);

// ✅ Límites de caracteres para cada campo
const limitesCaracteres = {
  nombres_ct: 25,
  apellidos_ct: 25,
  id_tipo_documento: 10,
  numero_documento_ct: 15,
  email_ct: 60,
  telefono_ct: 10
}

// ✅ Función para capitalizar cada palabra
const capitalizeWords = (str) => {
  return str
    .split(' ')
    .filter(word => word)
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(' ')
}

// ✅ Función para capitalizar al salir del campo
const handleBlur = (field) => {
  if (typeof form[field] === 'string') {
    form[field] = capitalizeWords(form[field].trim())
  }
}

// ✅ Función para limitar caracteres y actualizar el valor
const handleInput = (event, field) => {
  const maxCaracteres = limitesCaracteres[field] || 25
  form[field] = event.target.value.slice(0, maxCaracteres)
}

function getEstadoClass(estado) {
  if (estado === 'Inactivo' || estado === 'Pendiente') return 'h-5 w-5 bg-semaforo-rojo shadow-rojo rounded-full';
  if (estado === 'Suspendido') return 'bg-semaforo-amarillo';
  if (estado === 'Activo' || estado === 'Pagada') return 'h-5 w-5 bg-semaforo-verde shadow-verde rounded-full';
  return '';
}

const gotaClase = computed(() => coloresBg[appName.value]);

</script>

<template>

  <Head title="Editar cliente" />
  <SaludoOpciones :auth="auth" />
  <main class=" w-full h-[85vh] gap-4 py-3 px-6">
    <div class="righ w-full rounded-md">


      <!-- navegable -->
      <div class="options flex gap-1 items-center text-[14px] mt-4 cursor-pointer">
        <a :href="route('aplicacion.dashboard', { aplicacion, rol })" class="hover:text-essentials-secundary">
          <p>Dashboard</p>
        </a>
        <span class="material-symbols-rounded text-[18px]">chevron_right</span>
        <a class="hover:text-essentials-secundary">
          <p>Clientes</p>
        </a>
        <span class="material-symbols-rounded text-[18px]">chevron_right</span>
        <a class="hover:text-essentials-secundary">
          <p>Editar clientes</p>
        </a>
        <span class="material-symbols-rounded text-[18px]">chevron_right</span>
        <p class="font-bold">{{ cliente.nombre_completo }}</p>
      </div>
    </div>
    <div class="editarClientes flex items-center gap-3 w-full min-h-[80vh]">
      <div class="empleado w-[30%] h-full flex flex-col justify-center text-center">
            <div class="contenido ">

            <div class="imagen grid place-content-center my-3">
              <img src="https://plus.unsplash.com/premium_photo-1666298862681-c993ceb7865e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fGhvbWJyZXxlbnwwfHwwfHx8MA%3D%3D" class="rounded-full w-[150px] h-[150px] shadowM " alt="User">
            </div>
            <div class="nombre text-[25px]">
              <h1>{{ cliente.nombre_completo }}</h1>
            </div>
            <div class="rol text-[20px] -mt-[10px]">
              <p class="text-secundary-light">{{ cliente.rol.tipo_rol }}</p>
            </div>

            <div class="detallesCuenta my-2">
              <p class="text-secundary-light font-semibold text-left">Datos personales:</p>
              <div class="documento flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>id_card</span> {{ cliente.tipo_documento.documento_legal || 'Sin tipo de documento' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>tag</span> {{ cliente.numero_documento_ct }}</p>
              </div>

              <div class="email-telefono flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>email</span> {{ cliente.email_ct }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>phone</span> {{ cliente.telefono_ct }}</p>
              </div>
            </div>

            <div class="detallesCuenta my-2">
              <p class="text-secundary-light font-semibold text-left">Detalles membresía:</p>
              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>card_membership</span> {{
                      cliente.tienda?.aplicacion.membresia.nombre_membresia || 'Sin membresía' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>timer</span>{{ cliente.tienda.aplicacion.membresia.duracion || 'Pago pendiente' }} días</p>
              </div>

              <div class=" flex justify-end items-center">
               
                <!-- <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]" :class="[textClase]">timer</span>{{ 
                  cliente.tienda.pagos_membresia.dias_restantes || 'Sin duración' }} días restantes</p> -->
              </div>

              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>format_italic</span> {{
                      cliente.tienda.aplicacion.membresia.descripcion || 'Sin descripcion' }}</p>
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>history_toggle_off</span> {{
                      cliente.tienda.aplicacion.membresia.periodo || 'Sin periodo' }} </p>
              </div>

              <div class=" flex justify-between items-center">
                <p class="flex items-center gap-1"><span class="material-symbols-rounded text-[20px]"
                    :class='[textClase]'>attach_money</span>{{
                      formatCOP(cliente.tienda.aplicacion.membresia.precio) || 'Sin precio' }} COP</p>
                <div class="estadoCliente flex items-center gap-2 ">
                  <!-- <div class="p-1 h-3 w-3 rounded-[5px] font-bold"
                    :class="getEstadoClass(cliente.detalleCliente.tienda.aplicacion.membresia.estado.tipo_estado)"></div>
                  <p> {{ cliente.detalleCliente.tienda.aplicacion.membresia.estado.tipo_estado || 'Sin estado' }} </p> -->
                </div>
              </div>
            </div>
           
            <div class="estado text-[20px] flex justify-center items-center gap-2 my-2">
              <div class="gota" :class="getEstadoClass(cliente.estado.tipo_estado)"></div>
              <p>{{ cliente.estado.tipo_estado }}</p>
          </div>
        </div>
          </div>
    <div class="p-4 w-[70%]">
      <form @submit.prevent="submitForm">
       
        <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
            gap-3 flex flex-col items-center
            ">
          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <div class="
                contador-input 
                flex items-center justify-between
                xl:flex xl:items-center xl:justify-between
                ">
              <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Nombre:</p>
              <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-right  text-secundary-light
                  ">
                {{ form.nombres_ct.length }} / {{ limitesCaracteres.nombres_ct }}
              </p>
            </div>

            <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  xl:w-[100%] xl:p-[3px] xl:flex xl:items-center xl:gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light">
              <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded
                  " 
                  :class="[textClase]" >
                format_italic
              </span>
              

              <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tus nombres" v-model="form.nombres_ct"
                @input="handleInput($event, 'nombres_ct')" @blur="handleBlur('nombres_ct')" />
            </div>
          </div>

          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <div class="
                contador-input 
                flex items-center justify-between
                ">
              <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Apellidos:</p>
              <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-right text-secundary-light">
                {{ form.apellidos_ct.length }} / {{ limitesCaracteres.apellidos_ct }}
              </p>
            </div>

            <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light ">
              <span class="
                  text-[20px] pl-[5px]
                    material-symbols-rounded
                    "  :class="[textClase]">format_italic</span>

              <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tus apellidos" v-model="form.apellidos_ct"
                @input="handleInput($event, 'apellidos_ct')" @blur="handleBlur('apellidos_ct')" />
            </div>
          </div>
        </div>
        <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
            gap-3 flex flex-col items-center
            ">
          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <div class="
                contador-input 
                flex items-center justify-between
                xl:flex xl:items-center xl:justify-between
                ">
              <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Telefono:</p>
              <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-right  text-secundary-light
                  ">
                {{ form.telefono_ct.length }} / {{ limitesCaracteres.telefono_ct }}
              </p>
            </div>

            <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  xl:w-[100%] xl:p-[3px] xl:flex xl:items-center xl:gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light">
              <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded
                  "  :class="[textClase]">
                format_italic
              </span>

              <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tu telefono" v-model="form.telefono_ct"
                @input="handleInput($event, 'telefono_ct')" @blur="handleBlur('telefono_ct')" />
            </div>
          </div>

          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <div class="
                contador-input 
                flex items-center justify-between
                ">
              <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Email:</p>
              <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-right text-secundary-light">
                {{ form.email_ct.length }} / {{ limitesCaracteres.email_ct }}
              </p>
            </div>

            <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light ">
              <span class="
                  text-[20px] pl-[5px]
                    material-symbols-rounded
                    " :class="[textClase]">format_italic</span>

              <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tu email" v-model="form.email_ct" @input="handleInput($event, 'email_ct')"
                @blur="handleBlur('email_ct')" />
            </div>
          </div>
        </div>
        <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
             gap-3 flex flex-col items-center
            ">
          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Tipo de documento:</p>
            <div class="custom-select">

              <select v-model="form.id_tipo_documento" class="
                    w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                    ">

                <option v-for="tipo in tiposDocumento" :key="tipo.id" :value="tipo.id">
                  {{ tipo.documento_legal }}
                </option>
              </select>
              <div class="select-arrow"></div>
            </div>
          </div>

          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <div class="
                cotador-input 
                flex items-center justify-between
                ">
              <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Número documento:</p>
              <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-secundary-light text-right 
                  
                  ">
                {{ form.numero_documento_ct.length }} / {{ limitesCaracteres.numero_documento_ct }}
              </p>
            </div>
            <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light">
              <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded
                  " :class="[textClase]">pin</span>

              <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent" placeholder="Ingresa solo numeros"
                v-model="form.numero_documento_ct" @input="handleInput($event, 'numero_documento_ct')"
                @blur="handleBlur('numero_documento_ct')" />
            </div>

          </div>
        </div>
        <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
             gap-3 flex flex-col items-center
            ">
          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Aplicacion:</p>
            <div class="custom-select">
              <select v-model="form.id_aplicacion_web" class="
                   w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                  ">
                <optgroup v-for="grupo in aplicacionesAgrupadas" :key="grupo.id_membresia"
                  :label="`${grupo.id_membresia} - ${grupo.nombre_membresia}`">
                  <option v-for="app in grupo.apps" :key="app.id" :value="app.id">
                    {{ app.nombre_app }} App
                  </option>
                </optgroup>
              </select>

              <div class="select-arrow"></div>
            </div>
          </div>


          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Membresia:</p>
            <div class="custom-select">
              <select v-model="form.id_membresia" class="
                   w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                  ">
                <option v-for="membresia in membresias" :key="membresia.id" :value="membresia.id">{{
                  membresia.nombre_membresia }}</option>
              </select>

              <div class="select-arrow"></div>
            </div>
          </div>
        </div>
        <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
             gap-3 flex flex-col items-center
            ">
          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Tipo de rol:</p>
            <div class="custom-select">
              <select v-model="form.id_rol" class="
                   w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                  ">
                <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.tipo_rol }}
                </option>
              </select>

              <div class="select-arrow"></div>
            </div>
          </div>

          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Estado Cliente:</p>
            <div class="custom-select">
              <select v-model="form.id_estado" class="
                   w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                  ">
                <option v-for="estado in estados" :key="estado.id" :value="estado.id">{{
                  estado.tipo_estado }}</option>
              </select>

              <div class="select-arrow"></div>
            </div>
          </div>

          <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
            <p class="
                2xl:my-[5px] 2xl:text-[16px]
                xl:my-[4px] xl:text-[15px]
                ">Estado Token:</p>
            <div class="custom-select">
              <select v-model="form.id_estado_token" class="
                   w-full 
                    2xl:p-2
                    xl:p-1
                    border border-secundary-light rounded-md
                  ">
                <option v-for="estado in estados" :key="estado.id" :value="estado.id">{{
                  estado.tipo_estado }}</option>
              </select>

              <div class="select-arrow"></div>
            </div>
          </div>
        </div>

        <button class="opcion my-4 flex items-center gap-1 cursor-pointer px-4 rounded-lg" :class="[bgClase]">
          <p>Guardar cambios</p>
          <div class="icono flex justify-center items-center h-[40px] w-[40px]">
            <span class="material-symbols-rounded">save</span>
          </div>
        </button>
      </form>
    </div>
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
