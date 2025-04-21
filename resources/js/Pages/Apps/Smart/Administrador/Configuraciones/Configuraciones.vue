<script>
import { Head, usePage, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import BarraCalendario from '@/Components/BarraCalendario/BarraCalendario.vue';
import DetallesPlan from '@/Components/Dashboard/DetallesPlan.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import CardHistorial from '@/Components/Dashboard/CardHistorial.vue';
import dayjs from 'dayjs'
import 'dayjs/locale/es' // ✅ Importa el idioma español

dayjs.locale('es')

import { defineProps, ref, onMounted, onUnmounted, computed } from 'vue';


</script>

<script setup>
const props = defineProps({
  auth: {
    type: Object,
    required: true
  }
})

const user = props.auth.user
const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

const logout = () => {
    router.visit(route('logout'), {
        method: 'post',
        preserveScroll: true,
    });
};

const initials = computed(() => {
    const nombres = props.auth.user?.nombres_ct || '';
    const apellidos = props.auth.user?.apellidos_ct || '';

    const firstNameInitial = nombres.split(' ')[0]?.charAt(0).toUpperCase() || '';
    const lastNameInitial = apellidos.split(' ')[0]?.charAt(0).toUpperCase() || '';

    return firstNameInitial + lastNameInitial;
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

dayjs.locale('es');
const formatFecha = (fecha) => {
  if (!fecha || !dayjs(fecha).isValid()) return 'Sin fecha';
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a');
};

// ✅ Clases dinámicas según la aplicación
const colores = {
  'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
  'Essentials': 'bg-essentials-primary shadow-essentials rounded-full z-10 ',
  'Machine': 'bg-machine-primary shadow-machine',
  'Shopper': 'bg-shopper-primary shadow-shopper', 
  'Smart': 'bg-smart-primary shadow-smart rounded-full z-10 text-mono-negro',
  'default': 'bg-gray-300 shadow-gray-300'
};
const colores2 = {
  'TaurusCO': 'bg-universal-naranja',
  'Essentials': 'bg-essentials-primary rounded-full z-10  ',
  'Machine': 'bg-machine-primary',
  'Shopper': 'bg-shopper-primary', 
  'Smart': 'bg-smart-primary',
  'default': 'bg-gray-300 shadow-gray-300'
};

const hoverClass = computed(() => {
    switch (appName.value) {
        case 'TaurusCO':
            return 'hover:bg-universal-naranja';
        case 'Essentials':
            return 'hover:bg-essentials-primary';
        case 'Machine':
            return 'hover:bg-machine-primary';
        case 'Shopper':
            return 'hover:bg-shopper-primary';
        case 'Smart':
            return 'hover:bg-smart-primary hover:text-mono-negro';
        default:
            return 'hover:bg-gray-300';
    }
});

const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');
const bgFocus = computed(() => colores[appName.value]);
const bg = computed(() => colores2[appName.value]);




</script>

<template>
    <div>

        <Head title="Configuraciones" />

        <SaludoOpciones :auth="auth" />

        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />

            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px] mt-4">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Información de mi cuenta</p>
                </div>

                <div class="flex justify-between items-center w-full rounded-lg border border-secundary-light min-h-[100%] my-7 bg-secundary-default p-2">

                    <div class="leftUser">

                    </div>
                </div>

                <div class="w-full rounded-lg border border-secundary-light min-h-[100%] my-7 bg-secundary-default p-2">
                    <div class="header w-full h-[100px] rounded-md" :class="[bgFocus]"></div>
                    <div class="img-nombre -mt-[50px] mx-6">

                        <div class="logo flex gap-3 items-end">
                            <!-- Contenedor del usuario -->
                            <div class="user h-[120px] w-[120px] rounded-lg overflow-hidden flex items-center justify-center bg-black shadowM ">
                                <span class="text-white text-4xl font-bold">
                                    {{ initials }}
                                </span>
                            </div>

                            <!-- Información del usuario -->
                            <div class="logo">
                                <h3 class="font-semibold text-[25px]">{{ auth.user.nombres_ct }} {{
                                    auth.user.apellidos_ct }}</h3>
                                <p class="-mt-[8px] text-secundary-light text-[16px] font-medium">
                                    {{ auth.user.rol?.tipo_rol || 'Sin rol' }}
                                </p>
                            </div>
                        </div>

                        <div class="titulos my-2">
                            <h5 class="text-[30px] font-semibold">Información de mi cuenta</h5>
                            <p class="text-[17px] font-light">El apartado donde podras observar mas a detalle sobre tu cuenta.</p>
                        </div>
                        <hr class="h-[2px] w-full" />

                        <div class="opciones my-4 flex flex-col gap-4">
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
                        </div>

                    </div>

                </div>
                <button @click="logout"
                                    class="opcion flex items-center gap- shadow-essentials cursor-pointer px-4 rounded-lg" :class="[bgFocus]">
                                    <p>Salir</p>
                                    <div class="icono flex justify-center items-center h-[40px] w-[40px]">
                                        <span class="material-symbols-rounded">logout</span>
                                    </div>
                                </button>

            </main>


        </div>
    </div>
</template>
