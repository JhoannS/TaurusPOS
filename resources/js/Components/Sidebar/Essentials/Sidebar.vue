<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; // Asegúrate de importar Ziggy correctamente

const page = usePage(); // Accede a la información de la página actual
const currentRoute = computed(() => page.url); // Obtén la URL actual

import logo from '@images/logoTC.svg';

import { router } from '@inertiajs/vue3'

// Método de logout
const logout = () => {
  // Realiza la petición POST al endpoint de logout usando la ruta 'logout'
  router.post(route('logout'))
}
</script>



<template>
  <div class="h-[100vh] w-[60px] flex items-center p-[20px]">
    <aside class="w-[60px] flex justify-center flex-col gap-2 items-center bg-secundary-default p-2 rounded-full">
      <!-- Logo del Sidebar -->
      <div class="gota h-12 w-12 shadowM bg-essentials-primary rounded-full z-10">
        <img :src="logo" alt="Logo" class="h-12 w-12 rounded-full" />
      </div>

      <!-- Botón para Dashboard -->
      <div :class="currentRoute === 'essentials.admin.dashboard' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('essentials.admin.dashboard')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> space_dashboard </span>
        </a>
      </div>

      <!-- Botón para Multisucursales -->
      <div :class="currentRoute === 'essentials.admin.multisucursales' || currentRoute === 'essentials.admin.infoSucursal' || currentRoute === 'essentials.admin.crearItem' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('essentials.admin.multisucursales')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> swap_horiz </span>
        </a>
      </div>

      <!-- Botón para Swap -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> savings </span>
        </a>
      </div>

      <!-- Botón para Gastos -->
      <div :class="currentRoute === 'essentials.admin.gastos' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('essentials.admin.gastos')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> monitoring </span>
        </a>
      </div>

      <!-- Botón para Identidad -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> identity_platform </span>
        </a>
      </div>

      <!-- Botón para Calendar -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> calendar_clock </span>
        </a>
      </div>

      <!-- Botón para Checklist -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> checklist </span>
        </a>
      </div>

      <!-- Botón para Etiquetado -->
      <div :class="currentRoute === 'essentials.admin.etiquetado' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('essentials.admin.etiquetado')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> barcode_scanner </span>
        </a>
      </div>

      <!-- Botón para Generador de QR -->
      <div :class="currentRoute === 'essentials.admin.generadorQrs' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('essentials.admin.generadorQrs')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> qr_code_2 </span>
        </a>
      </div>

      <!-- Botón para Donut -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> donut_small </span>
        </a>
      </div>

      <!-- Botón para Diversidad -->
      <div :class="currentRoute === 'swap' ? 'btn-link-essentials' : 'btn-link-disable'">
        <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
          <span class="material-symbols-rounded"> diversity_1 </span>
        </a>
      </div>

      
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
