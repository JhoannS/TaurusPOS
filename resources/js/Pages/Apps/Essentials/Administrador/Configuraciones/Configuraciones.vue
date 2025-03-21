<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import { defineProps } from 'vue';


export default {
  name: 'Configuraciones',
  components: {
    Head,
  },
}
</script>

<script setup>
// Asegúrate de tener 'auth' en las props del componente
defineProps({
  auth: Object,

})
import { ref, onMounted, onUnmounted } from 'vue';

</script>

<template>
    <div>

        <Head title="Configuraciones" />

        <h1>Configuraciones</h1>
        
    </div>
</template>
