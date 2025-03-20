<template>
    <div class="h-[100vh] w-[60px] flex items-center p-[20px]">
      <aside class="w-[60px] flex justify-center flex-col gap-2 items-center bg-secundary-default p-2 rounded-full">
        <div class="gota h-12 w-12 shadow-guru bg-guru-primary rounded-full"></div>

  
        <!-- Botón para Dashboard -->
        <div :class="currentRoute === 'guru.admin.dashboard' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('guru.admin.dashboard')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> space_dashboard </span>
          </a>
        </div>
  
        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> swap_horiz </span>
          </a>
        </div>

        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> savings </span>
          </a>
        </div>

        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> monitoring </span>
          </a>
        </div>

        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> identity_platform </span>
          </a>
        </div>

        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> calendar_clock </span>
          </a>
        </div>
        
        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> checklist </span>
          </a>
        </div>
        
        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> barcode_scanner </span>
          </a>
        </div>

        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> qr_code_2 </span>
          </a>
        </div>
        
    <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> donut_small </span>
          </a>
        </div>
       
        <!-- Botón para Swap -->
        <div :class="currentRoute === 'swap' ? 'btn-link-guru' : 'btn-link-disable'">
          <a :href="route('swap')" class="bg-transparent flex items-center justify-center">
            <span class="material-symbols-rounded"> diversity_1 </span>
          </a>
        </div>
      </aside>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { usePage } from '@inertiajs/inertia-vue3';
  import { route } from 'ziggy-js'; // Importación nombrada
  
  const page = usePage();
  const currentRoute = computed(() => page.props.value.currentRoute);
  </script>
  