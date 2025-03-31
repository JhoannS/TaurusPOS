<script setup>

</script>

<template>
  <div class="flex flex-col items-center justify-center h-screen text-center">
    <h1 class="text-6xl font-bold text-red-500">500</h1>
    <p class="text-lg mt-4">Página no encontrada</p>
    <button @click="$inertia.visit('/')" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md">
      Volver al inicio
    </button>
  </div>
</template>
