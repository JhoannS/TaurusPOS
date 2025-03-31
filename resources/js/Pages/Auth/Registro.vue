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
  telefono_ct: '3',
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

    <div class="bg-mono-negro
            sm:bg-green-500 
            md:bg-yellow-500
            lg:bg-red-500 
            xl:bg-mono-negro 
            2xl:bg-mono-negro
            flex justify-center items-center
            
            ">
      <main class="
      2xl:w-[100%] 2xl:p-[80px] 2xl:gap-16
      xl:w-[100%] xl:px-[80px] xl:gap-14 xl:py-[0px]
      min-h-[100vh] flex items-center justify-between w-[100%] p-[40px] gap-14
      ">
        <div class="
        left 
        2xl:w-[65%]
        xl:w-[65%]
        w-full
        ">
          <div class="logo 
          2xl:flex 2xl:gap-3 2xl:items-center
          xl:flex xl:gap-2 xl:items-center
          flex items-center gap-2
          ">
            <div class="gota 
            2xl:h-7 2xl:w-10
            xl:h-6 xl:w-9
            h-5 w-8 rounded-full shadow-universal-naranja bg-universal-naranja
            "></div>
            <div class="logo">
              <h1 class="
              text-[20px] 
              font-semibold
              ">Taurus Comunity CO</h1>
              <p class="
              -mt-[8px] text-[14px] 
              font-medium
              ">Empresa de software especializada</p>
            </div>
          </div>

          <div class="welcome">
            <h2 class="
            2xl:text-[35px] d 2xl:mt-[20px]
            xl:text-[25px] d xl:mt-[15px]
            font-bold text-[22px] mt-3
            ">Registrate y empecemos👋</h2>
            <p class="
            2xl:text-[20px]
            xl:text-[18px]
            text-[15px]
            ">No te arrepentiras de unirte a la familia Taurus Comunity, especializado en software
            </p>
          </div>


          <form @submit.prevent="submit" class="
          2xl:mt-5 2xl:flex 2xl:flex-col 2xl:gap-2
          xl:mt-4 xl:flex xl:flex-col xl:gap-2
          mt-3 flex flex-col gap-3
          ">

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
                  transition-all rounded-[5px] border-[1px] border-secundary-light" :class="{ 'border-universal-naranja': form.errors.nombres_ct }
                    ">
                  <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded text-universal-naranja 
                  ">
                    format_italic
                  </span>

                  <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tus nombres" v-model="form.nombres_ct"
                    @input="handleInput($event, 'nombres_ct')" @blur="handleBlur('nombres_ct')" />
                </div>

                <span v-if="form.errors.nombres_ct" class="text-universal-naranja text-sm
                ">
                  {{ form.errors.nombres_ct }}
                </span>
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
                  transition-all rounded-[5px] border-[1px] border-secundary-light " :class="{ 'border-universal-naranja': form.errors.apellidos_ct }
                    ">
                  <span class="
                  text-[20px] pl-[5px]
                    material-symbols-rounded text-universal-naranja 
                    ">format_italic</span>

                  <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tus apellidos" v-model="form.apellidos_ct"
                    @input="handleInput($event, 'apellidos_ct')" @blur="handleBlur('apellidos_ct')" />
                </div>
                <span v-if="form.errors.apellidos_ct" class="
                2xl:text-sm
                text-universal-naranja 
                ">
                  {{ form.errors.apellidos_ct }}
                </span>
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
                    <option value="" disabled selected>Selecciona un tipo</option>
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
                  transition-all rounded-[5px] border-[1px] border-secundary-light" :class="{ 'border-universal-naranja': form.errors.numero_documento_ct }
                    ">
                  <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded text-universal-naranja 
                  ">pin</span>

                  <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent" placeholder="Ingresa solo numeros"
                    v-model="form.numero_documento_ct" @input="handleInput($event, 'numero_documento_ct')"
                    @blur="handleBlur('numero_documento_ct')" />
                </div>
                <span v-if="form.errors.numero_documento_ct" class="
                2xl:text-sm
                text-universal-naranja 
                ">
                  {{ form.errors.numero_documento_ct }}
                </span>
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
                ">
                  <p class="
                  2xl:my-[5px] 2xl:text-[16px]
                  xl:my-[4px] xl:text-[15px]
                  ">Número telefono:</p>
                  <p class="
                  2xl:text-[10px]                  
                  xl:text-[12px]
                  text-[8px]
                  text-secundary-light text-right 
                  ">
                    {{ form.telefono_ct.length }} / {{ limitesCaracteres.telefono_ct }}
                  </p>
                </div>

                <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light" :class="{ 'border-universal-naranja': form.errors.telefono_ct }
                    ">
                  <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded text-universal-naranja 
                  ">phone</span>

                  <input type="text" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa solo números" v-model="form.telefono_ct"
                    @input="handleInput($event, 'telefono_ct')" @blur="handleBlur('telefono_ct')" />

                </div>
                <span v-if="form.errors.telefono_ct" class="
                2xl:text-sm
                text-universal-naranja 
                ">
                  {{ form.errors.telefono_ct }}
                </span>
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
                  text-right text-secundary-light
                  ">
                    {{ form.email_ct.length }} / {{ limitesCaracteres.email_ct }}
                  </p>
                </div>
                <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light" :class="{ 'border-universal-naranja': form.errors.email_ct }
                    ">
                  <span class="
                  material-symbols-rounded 
                  text-[20px] pl-[5px]
                  text-universal-naranja 
                  ">email</span>

                  <input type="email" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent
                    " placeholder="Ingresa tus email" v-model="form.email_ct" @input="handleInput($event, 'email_ct')"
                    @blur="handleBlur('email_ct')" />
                </div>
                <span v-if="form.errors.email_ct" class="
                2xl:text-sm
                text-universal-naranja 
                ">
                  {{ form.errors.email_ct }}
                </span>
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
                my-[5px] text-[14px]
                ">Ingresa una contraseña:</p>

                <div class="
                  w-[100%] 
                  p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-ligh" t :class="{ 'border-universal-naranja': form.errors.contrasenia_ct }
                    ">
                  <span class="material-symbols-rounded text-universal-naranja text-[20px] pl-[5px]
                  ">password</span>

                  <input type="password" class="
                    2xl:w-full
                     focus:outline-none focus:border-none font-normal bg-transparent" placeholder="Minimo 6 digitos"
                    v-model="form.contrasenia_ct" />
                </div>
                <span v-if="form.errors.contrasenia_ct" class="
                2xl:text-sm
                text-universal-naranja 
                ">
                  {{ form.errors.contrasenia_ct }}
                </span>
              </div>

              <div class="
              2xl:w-[50%]
              xl:w-[50%]
              w-full
              ">
                <p class="
                my-[5px] text-[14px]
                ">Confirmar contraseña:</p>
                <div class="
                  w-[100%] p-[3px] flex items-center gap-[8px]
                  transition-all rounded-[5px] border-[1px] border-secundary-light 
                  ">
                  <span class="
                  text-[20px] pl-[5px]
                  material-symbols-rounded text-universal-naranja 
                  ">password</span>

                  <input type="password" class="
                    w-full 
                    focus:outline-none focus:border-none font-normal bg-transparent"
                    placeholder="Debe ser igual la contraseña" v-model="form.contrasenia_ct_confirmation" />
                </div>
              </div>
            </div>
            <div class="
            2xl:flex 2xl:flex-row 2xl:justify-between 2xl:items-center 2xl:gap-2
            xl:flex xl:flex-row xl:justify-between xl:items-center xl:gap-2
            gap-3 flex flex-col items-center
            ">
              <div class="
              w-[100%]
              ">
                <p class="
                2xl:my-[5px] vtext-[16px]
                ">Aplicacion de interes:</p>
                <div class="custom-select">
                  <select v-model="form.id_aplicacion" class="
                    w-full 2xl:p-2 b
                    order border-secundary-light rounded-md
                    ">
                    <option value="" disabled selected>Selecciona una app</option>
                    <option v-for="app in aplicaciones" :key="app.id" :value="app.id">
                      {{ app.nombre_app }} App
                    </option>
                  </select>
                  <div class="select-arrow"></div>
                </div>
              </div>
            </div>


            <a href="https://api.whatsapp.com/send/?phone=573219631459&text=Vengo+desde+la+app%2C+quiero+activar+mi+token+por+favor.&type=phone_number&app_absent=0"
              class="
              2xl:my-3
              text-right text-universal-azul 
              ">Contactanos para la activación</a>

            <button type="submit" class="btn-taurus">Crear cuenta <span
                class="material-symbols-rounded bg-transparent">bolt</span></button>

            <p class="
            
            mt-4
            ">¿Ya eres parte de Taurus Comunity?, <a :href="route('login.auth')" class="
                text-universal-azul
                ">Inicia Sesion</a>.</p>

            <p class="
             text-[12px]
            text-universal-azul 
            ">Versión Deimos 1.0.0</p>

          </form>
        </div>
        <div class="
        2xl:w-[34%] 2xl:flex 2xl:justify-center 2xl:items-center 2xl:visible 2xl:p-0
        xl:w-[35%] xl:flex xl:justify-center xl:items-center xl:visible xl:p-0

        hidden right shadowM rounded-lg
        ">

          <img
            src="https://images.unsplash.com/photo-1638657527755-ca3e0d217260?q=80&w=1376&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt="Img login" class="
            rounded-lg
            " />

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