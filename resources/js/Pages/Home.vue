<script>
import { Head } from '@inertiajs/vue3';

export default {
  name: 'Home',
  components: {
    Head,
  },
}
</script>


<template>
  <div>
    <Head title="Página de Inicio" />
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold">Bienvenido a Mi Aplicación</h1>
    </div>
  </div>
</template>

