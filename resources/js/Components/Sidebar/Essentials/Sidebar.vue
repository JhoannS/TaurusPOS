<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

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

import logo from '@images/logoTC.svg';
import { router } from '@inertiajs/vue3';

const logout = () => {
  router.post(route('logout'));
};
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
        <a :href="route('aplicacion.dashboard', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> space_dashboard </span>
        </a>
      </div>

      <!-- Botón para Multisucursales -->
      <div  :class="currentRoute === multisucursalRoute ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.multisucursales', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> swap_horiz </span>
        </a>
      </div>

      <!-- Botón para Swap -->
      <div  :class="currentRoute === route('aplicacion.dashboard', { aplicacion, rol }) ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.multisucursales', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> savings </span>
        </a>
      </div>

      <!-- Botón para Gastos -->
      <div :class="currentRoute === route('aplicacion.gastos', { aplicacion, rol }) ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.gastos', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> monitoring </span>
        </a>
      </div>

      <!-- Botón para Identidad
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> identity_platform </span>
        </a>
      </div>

       Botón para Calendar 
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> calendar_clock </span>
        </a>
      </div>

       Botón para Checklist 
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> checklist </span>
        </a>
      </div> -->

      <!-- Botón para Etiquetado -->
      <div :class="currentRoute === route('aplicacion.etiquetado', { aplicacion, rol }) ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.etiquetado', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> barcode_scanner </span>
        </a>
      </div>

      <!-- Botón para Generador de QR -->
      <div :class="currentRoute === route('aplicacion.generadorQrs', { aplicacion, rol }) ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('aplicacion.generadorQrs', { aplicacion, rol })" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> qr_code_2 </span>
        </a>
      </div>

      <!-- Botón para Donut 
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> donut_small </span>
        </a>
      </div>

       Botón para Diversidad 
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> diversity_1 </span>
        </a>
      </div> -->

      
      <!-- Botón para Diversidad -->
      <div @click="logout" class="cursor-pointer">
        <a class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> logout </span>
        </a>
      </div>
    </aside>
  </div>
</template>

<style scoped>
/* Aquí puedes agregar estilos adicionales */
</style>
