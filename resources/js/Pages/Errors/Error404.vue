<script setup>
import { Head } from '@inertiajs/vue3';

</script>

<template>
  <Head title="Oh, oh. Página no encontrada" />

  <div class="flex flex-col items-center justify-center h-screen text-center">
    <h1 class="text-6xl font-bold text-red-500">404</h1>
    <p class="text-lg mt-4">Página no encontrada</p>
    <button @click="$inertia.visit('/')" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md">
      Volver al inicio
    </button>
  </div>

 
</template>
