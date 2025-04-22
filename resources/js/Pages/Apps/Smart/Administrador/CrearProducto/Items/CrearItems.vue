<script setup>

import { Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, computed } from 'vue'; 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js'; // Importación nombrada
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import CrearItem from '@/Components/CrearProductos/CrearItem.vue';


const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const page = usePage();
const currentRoute = computed(() => page.props.value.currentRoute);

const searchQuery = ref('');
</script>

<template>
    <div>

        <Head title="Info de mi sucursal" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth"/>

            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones :auth="auth"/>

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px]">
                    <a class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a class="hover:text-essentials-secundary">
                        <p>Sucursales activas</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <a  class="hover:text-essentials-secundary">
                        <p>Gestionar mi sucursal</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Agregar nuevo item</p>
                </div>

                

                <CrearItem />
                
            </main>


        </div>
    </div>
</template>
