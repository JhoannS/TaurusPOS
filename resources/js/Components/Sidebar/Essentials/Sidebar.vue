<script>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import logo from '@images/logoTC.svg';
</script>

<script setup>
// Traer datos del usuario logeuado
const props = defineProps({
  auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

// Normaliza las rutas para que la comparación funcione
const currentRoute = computed(() => new URL(page.url, window.location.origin).pathname);
const dashboardRoute = computed(() => new URL(route('aplicacion.dashboard', { aplicacion, rol }), window.location.origin).pathname);
const multisucursalRoute = computed(() => new URL(route('aplicacion.multisucursales', { aplicacion, rol }), window.location.origin).pathname);
const overviewRoute = computed(() => new URL(route('aplicacion.overviews', { aplicacion, rol }), window.location.origin).pathname);
const gastosRoute = computed(() => new URL(route('aplicacion.gastos', { aplicacion, rol }), window.location.origin).pathname);
const usuariosRoute = computed(() => new URL(route('aplicacion.usuarios', { aplicacion, rol }), window.location.origin).pathname);
const reservasRoute = computed(() => new URL(route('aplicacion.reservas', { aplicacion, rol }), window.location.origin).pathname);
const inventarioRoute = computed(() => new URL(route('aplicacion.inventarios', { aplicacion, rol }), window.location.origin).pathname);
const codigoBarrasRoute = computed(() => new URL(route('aplicacion.codigoBarras', { aplicacion, rol }), window.location.origin).pathname);
const generadorQrsRoute = computed(() => new URL(route('aplicacion.generadorQrs', { aplicacion, rol }), window.location.origin).pathname);
const ordenTrabajoRoute = computed(() => new URL(route('aplicacion.ordenTrabajos', { aplicacion, rol }), window.location.origin).pathname);
const resenasRoute = computed(() => new URL(route('aplicacion.resenas', { aplicacion, rol }), window.location.origin).pathname);
</script>


<template>
  <div class="h-[100vh] w-[60px] flex items-center p-[20px]">
    <aside class="w-[60px] flex justify-center flex-col gap-2 items-center bg-secundary-default p-2 rounded-full">
      <!-- Logo del Sidebar -->
      <div class="gota h-12 w-12 shadowM bg-essentials-primary rounded-full z-10">
        <img :src="logo" alt="Logo" class="h-12 w-12 rounded-full" />
      </div>

      <!-- Botón para Dashboard -->
      <div :class="currentRoute === dashboardRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> space_dashboard </span>
        </a>
      </div>

      <!-- Botón para Multisucursales -->
      <div :class="currentRoute === multisucursalRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.multisucursales', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> swap_horiz </span>
        </a>
      </div>

      <!-- Botón para Overviews -->
      <div :class="currentRoute === overviewRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.overviews', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> savings </span>
        </a>
      </div>

      <!-- Botón para Gastos -->
      <div :class="currentRoute === gastosRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.gastos', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> monitoring </span>
        </a>
      </div>

      <!-- Botón para usuarios -->
      <div :class="currentRoute === usuariosRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.usuarios', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> identity_platform </span>
        </a>
      </div>

      <!-- Botón para reservas -->
      <div :class="currentRoute === reservasRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.reservas', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> calendar_clock </span>
        </a>
      </div>

      <!-- Botón para inventario -->
      <div :class="currentRoute === inventarioRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.inventarios', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> checklist </span>
        </a>
      </div>

      <!-- Botón para Etiquetado -->
      <div :class="currentRoute === codigoBarrasRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.codigoBarras', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> barcode_scanner </span>
        </a>
      </div>

      <!-- Botón para Generador de QR -->
      <div :class="currentRoute === generadorQrsRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.generadorQrs', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> qr_code_2 </span>
        </a>
      </div>

      <!-- Botón para orden trabajo -->
      <div :class="currentRoute === ordenTrabajoRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.ordenTrabajos', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> donut_small </span>
        </a>
      </div>

      <!-- Botón para resenas -->
      <div :class="currentRoute === resenasRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.resenas', { aplicacion, rol })"
          class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> diversity_1 </span>
        </a>
      </div>
    </aside>
  </div>
</template>
