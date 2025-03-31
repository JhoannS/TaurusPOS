<template>
    <div class="flex flex-col items-center justify-center h-screen text-center">
      <h1 class="text-6xl font-bold text-red-500">{{ errorCode }}</h1>
      <p class="text-lg mt-4">{{ errorMessage }}</p>
      <button @click="$inertia.visit('/')" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md">
        Volver al inicio
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      status: Number
    },
    computed: {
      errorCode() {
        return this.status || 404;
      },
      errorMessage() {
        switch (this.status) {
          case 403:
            return "No tienes permisos para acceder a esta página.";
          case 404:
            return "Página no encontrada.";
          case 500:
            return "Error interno del servidor.";
          default:
            return "Ocurrió un error inesperado.";
        }
      }
    }
  };
  </script>
  