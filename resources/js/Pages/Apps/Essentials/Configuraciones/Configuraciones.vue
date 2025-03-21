<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';



export default {
    name: 'Configuraciones',
    components: {
        Head,
    },
}
</script>

<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
  auth: Object,
});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

</script>

<template>
    <div>

        <Head title="Configuraciones" />


       
    </div>
</template>
