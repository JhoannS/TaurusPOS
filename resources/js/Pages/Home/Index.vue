<script setup>
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

</script>

<template>

  <Head title="Bienvenido a Taurus Comunity" />
  <div>

    <header class="p-4 flex justify-between items-center">
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
          <h1 class="text-[20px] font-semibold">Taurus Comunity CO</h1>
          <p class="-mt-[8px] text-[14px] font-medium">Empresa de software especializada</p>
        </div>
      </div>
      <div class="btnAcciones flex items-center gap-4
      ">
        <button class="btn-taurus">
        <a :href="route('login.auth')" class="">Inicia sesión</a>
      </button>
      <button class="rounded-lg p-1">
        <a :href="route('register.auth')" class="">Registrarme</a>
      </button>
      </div>
      
    </header>

  </div>
</template>