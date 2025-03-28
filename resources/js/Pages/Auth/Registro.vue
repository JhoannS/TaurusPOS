<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';


defineProps({
  tiposDocumento: {
    type: Array,
    required: true,
  },
  aplicaciones: {
    type: Array,
    required: true
  }
})

const page = usePage();
const currentRoute = computed(() => new URL(page.url, window.location.origin).pathname);

const form = useForm({
  nombres_ct: '',
  apellidos_ct: '',
  id_tipo_documento: '',
  numero_documento_ct: '',
  email_ct: '',
  telefono_ct: '',
  contrasenia_ct: '',
  contrasenia_ct_confirmation: '',
  id_aplicacion: ''
})

// ✅ Estado para la notificación
const mensajeNotificacion = ref('')
const tipoNotificacion = ref(null) // 'success' o 'error'
const mostrarNotificacion = ref(false)

// ✅ Función para mostrar la notificación
const mostrarMensaje = (mensaje, tipo) => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true

  // Ocultar la notificación después de 3 segundos
  setTimeout(() => {
    mostrarNotificacion.value = false
  }, 5000)
}

// ✅ Función para enviar el formulario
const submit = () => {
  form.post('/register/auth', {
    onSuccess: () => {
      mostrarMensaje('Registro exitoso', 'success')
    },
    onError: () => {
      mostrarMensaje('Error al registrar, verifica los datos.', 'error')
    }
  })
}
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
</script>

<template>
  <div>

    <Head title="Registrate" />
    <div class="bg-mono-negro flex justify-center items-center">
      <main class="flex items-center justify-between min-h-[100vh] w-[80%] p-[80px] gap-16">
        <div class="left w-[60%]">
          <div class="logo flex gap-3 items-center">
            <div class="gota h-7 w-10 shadow-essentials bg-universal-naranja rounded-full"></div>
            <div class="logo">
              <h1 class="text-[20px] font-semibold">Taurus Comunity CO</h1>
              <p class="-mt-[8px] text-[14px] font-medium">Software profesional Essentials App</p>
            </div>
          </div>

          <div class="welcome">
            <h2 class="text-[35px] font-bold mt-[20px]">Registrate y empecemos👋</h2>
            <p class="text-[20px]">No te arrepentiras de unirte a la familia Taurus Comunity, especializado en software
            </p>
          </div>


          <form @submit.prevent="submit" class="mt-5 flex flex-col gap-2">

            <div class="flex justify-between items-center gap-2">
              <div class="w-[50%]">
                <div class="contador-input flex items-center justify-between">
                  <p class="my-[5px] text-[16px]">Nombre:</p>
                  <p class="text-right text-[10px] text-gray-500">
                    {{ form.nombres_ct.length }} / {{ limitesCaracteres.nombres_ct }}
                  </p>
                </div>

                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.nombres_ct }">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">
                    format_italic
                  </span>

                  <input type="text"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Ingresa tus nombres" v-model="form.nombres_ct"
                    @input="handleInput($event, 'nombres_ct')" @blur="handleBlur('nombres_ct')" />
                </div>

                <span v-if="form.errors.nombres_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.nombres_ct }}
                </span>
              </div>

              <div class="w-[50%]">
                <div class="contador-input flex items-center justify-between">
                  <p class="my-[5px] text-[16px]">Apellidos:</p>
                  <p class="text-right text-[10px] text-gray-500">
                    {{ form.apellidos_ct.length }} / {{ limitesCaracteres.apellidos_ct }}
                  </p>
                </div>

                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.apellidos_ct }">
                  <span
                    class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">format_italic</span>

                  <input type="text"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Ingresa tus apellidos" v-model="form.apellidos_ct"
                    @input="handleInput($event, 'apellidos_ct')" @blur="handleBlur('apellidos_ct')" />
                </div>
                <span v-if="form.errors.apellidos_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.apellidos_ct }}
                </span>
              </div>
            </div>
            <div class="flex justify-between items-center gap-2">
              <div class="w-[50%]">
                <p class="my-[5px] text-[16px]">Tipo de documento:</p>
                <div class="custom-select">
                  <select v-model="form.id_tipo_documento"
                    class="w-full p-2 bg-mono-negro text-white border border-secundary-light rounded-md">
                    <option value="" disabled selected>Selecciona un tipo</option>
                    <option v-for="tipo in tiposDocumento" :key="tipo.id" :value="tipo.id">
                      {{ tipo.documento_legal }}
                    </option>
                  </select>
                  <div class="select-arrow"></div>
                </div>
              </div>

              <div class="w-[50%]">
                <div class="contador-input flex items-center justify-between">
                  <p class="my-[5px] text-[16px]">Número documento:</p>
                  <p class="text-right text-[10px] text-gray-500">
                    {{ form.numero_documento_ct.length }} / {{ limitesCaracteres.numero_documento_ct }}
                  </p>
                </div>
                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.numero_documento_ct }">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">pin</span>

                  <input type="text"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Ingresa solo numeros" v-model="form.numero_documento_ct"
                    @input="handleInput($event, 'numero_documento_ct')" @blur="handleBlur('numero_documento_ct')" />
                </div>
                <span v-if="form.errors.numero_documento_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.numero_documento_ct }}
                </span>
              </div>
            </div>
            <div class="flex justify-between items-center gap-2">
              <div class="w-[50%]">
                <div class="contador-input flex items-center justify-between">
                  <p class="my-[5px] text-[16px]">Número telefono:</p>
                  <p class="text-right text-[10px] text-gray-500">
                    {{ form.telefono_ct.length }} / {{ limitesCaracteres.telefono_ct }}
                  </p>
                </div>

                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.telefono_ct }">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">phone</span>

                  <input type="text"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Ingresa solo números" v-model="form.telefono_ct"
                    @input="handleInput($event, 'telefono_ct')" @blur="handleBlur('telefono_ct')" />

                </div>
                <span v-if="form.errors.telefono_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.telefono_ct }}
                </span>
              </div>

              <div class="w-[50%]">
                <div class="contador-input flex items-center justify-between">
                  <p class="my-[5px] text-[16px]">Email:</p>
                  <p class="text-right text-[10px] text-gray-500">
                    {{ form.email_ct.length }} / {{ limitesCaracteres.email_ct }}
                  </p>
                </div>
                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.email_ct }">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">email</span>

                  <input type="email"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Ingresa tus email" v-model="form.email_ct" @input="handleInput($event, 'email_ct')"
                    @blur="handleBlur('email_ct')" />
                </div>
                <span v-if="form.errors.email_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.email_ct }}
                </span>
              </div>
            </div>
            <div class="flex justify-between items-center gap-2">
              <div class="w-[50%]">
                <p class="my-[5px] text-[14px]">Ingresa una contraseña:</p>

                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]"
                  :class="{ 'border-universal-naranja': form.errors.contrasenia_ct }">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">password</span>

                  <input type="password"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Minimo 6 digitos" v-model="form.contrasenia_ct" />
                </div>
                <span v-if="form.errors.contrasenia_ct" class="text-universal-naranja text-sm">
                  {{ form.errors.contrasenia_ct }}
                </span>
              </div>

              <div class="w-[50%]">
                <p class="my-[5px] text-[14px]">Confirmar contraseña:</p>
                <div
                  class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]">password</span>

                  <input type="password"
                    class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro text-blanco"
                    placeholder="Debe ser igual la contraseña" v-model="form.contrasenia_ct_confirmation" />
                </div>
              </div>
            </div>
            <div class="flex justify-between items-center gap-2">
              <div class="w-[100%]">
                <p class="my-[5px] text-[16px]">Aplicacion de interes:</p>
                <div class="custom-select">
                  <select v-model="form.id_aplicacion"
                    class="w-full p-2 bg-mono-negro text-white border border-secundary-light rounded-md">
                    <option value="" disabled selected>Selecciona una app</option>
                    <option v-for="app in aplicaciones" :key="app.id" :value="app.id">
                      {{ app.nombre_app }}
                    </option>
                  </select>
                  <div class="select-arrow"></div>
                </div>
              </div>
            </div>


            <a href="https://api.whatsapp.com/send/?phone=573219631459&text=Vengo+desde+la+app%2C+quiero+activar+mi+token+por+favor.&type=phone_number&app_absent=0"
              class="text-universal-azul text-right">Contactanos para la activación</a>

            <button type="submit" class="btn-taurus">Crear cuenta <span
                class="material-symbols-rounded bg-transparent">bolt</span></button>
            <div class="bg-blue-500 text-sm 
            sm:bg-green-500 sm:text-base 
            md:bg-yellow-500 md:text-lg 
            lg:bg-red-500 lg:text-xl
            xl:bg-purple-500 xl:text-2xl 
            2xl:bg-pink-500 2xl:text-3xl h-2 rounded-lg">
            </div>

            <p class="text-center">¿Ya eres parte de Taurus Comunity?, <a :href="route('login.auth')"
                class="text-universal-azul">Inicia Sesion</a>.</p>

            <p class="text-universal-azul text-center text-[12px]">Versión Deimos 1.0.0</p>

          </form>
        </div>
        <div class="right w-[40%] rounded-lg flex justify-center items-center shadowM">

          <img
            src="https://images.unsplash.com/photo-1638657527755-ca3e0d217260?q=80&w=1376&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt="Img login" class="rounded-lg" />

        </div>

      </main>
      <!-- ✅ Notificación de éxito -->
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
    </div>
  </div>
</template>