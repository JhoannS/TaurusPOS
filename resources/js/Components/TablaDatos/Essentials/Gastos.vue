<script setup>
import { computed } from 'vue';
import logo from '@images/logoTC.svg'


const props = defineProps({
    gastos: {
        type: Array,
        default: () => [
            {
                id: 1,
                tipo_gasto: 'Seleccion A',
                monto: '5000',
                asunto: 'Prueba 1',
                descripcion: 'compra de vasos',
                fecha_creacion: 'Lun 17, enero de 2025',
            },
            {
                id: 2,
                tipo_gasto: 'Seleccion B',
                monto: '4000',
                asunto: 'Prueba 2',
                descripcion: 'compra de vasos',
                fecha_creacion: 'Lun 17, enero de 2025',

            },
            {
                id: 3,
                tipo_gasto: 'Seleccion C',
                monto: '3000',
                asunto: 'Prueba 3',
                descripcion: 'compra de vasos',
                fecha_creacion: 'Lun 17, enero de 2025',

            }
        ]
    },
    searchQuery: {
        type: String,
        default: ''
    }
});

const filteredGastos = computed(() => {
    if (!props.gastos || !Array.isArray(props.gastos)) return [];
    if (!props.searchQuery.trim()) return props.gastos;

    const query = props.searchQuery.toLowerCase().trim();

    return props.gastos.filter(gasto => {
        return (
            // Búsqueda exacta en valores específicos
            String(gasto.id).toLowerCase() === query ||
            gasto.tipo_gasto.toLowerCase().includes(query) ||
            String(gasto.monto).toLowerCase() === query ||
            gasto.asunto.toLowerCase().includes(query) ||
            gasto.descripcion.toLowerCase() === query || // Busca el estado completo, no fragmentos
            gasto.fecha_creacion.toLowerCase().includes(query)
           
        );
    });
});

function handleBeforeEnter(el) {
    el.classList.add('animate-fadeIn');
}

function handleBeforeLeave(el) {
    el.classList.add('animate-fadeOut');
}
</script>

<template>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse" id="tabla">
            <thead>
                <tr class="border border-secundary-light">
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Tipo de gasto</th>
                    <th class="p-2 text-left">Monto</th>
                    <th class="p-2 text-left">Asunto</th>
                    <th class="p-2 text-left">Descripción</th>
                    <th class="p-2 text-left">Fecha creación</th>
                    <th class="p-2 text-left">Acciones</th>
                </tr>
            </thead>
            <transition-group tag="tbody" @before-enter="handleBeforeEnter" @before-leave="handleBeforeLeave">
                <tr v-for="gasto in filteredGastos" :key="gasto.id">
                    <td class="text-[14px] p-2">{{ gasto.id }}</td>
                    <td class="text-[14px] p-2">{{ gasto.tipo_gasto }}</td>
                    <td class="text-[14px] p-2">
                        ${{ gasto.monto }}
                    </td>
                    <td class="text-[14px] p-2">{{ gasto.asunto }}</td>
                    <td class="text-[14px] p-2">{{ gasto.descripcion }}</td>
                    <td class="text-[14px] p-2">{{ gasto.fecha_creacion }}</td>
                    
                    
                    <td>
                        <div class="flex gap-2 items-center">
                            <span class="material-symbols-rounded hover:text-essentials-primary cursor-pointer">
                                delete
                            </span>
                            <span class="material-symbols-rounded hover:text-blue-400 cursor-pointer">
                                eye_tracking
                            </span>
                        </div>
                    </td>
                </tr>
            </transition-group>
        </table>

        <div v-if="filteredGastos.length === 0" class="text-center mt-10 font-bold text-2xl text-gray-500">
            No hay coincidencias.
        </div>
    </div>
</template>
