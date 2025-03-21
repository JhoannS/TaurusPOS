<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';
import GeneradorEtiquetas from '@/Components/Etiquetas/GeneradorEtiquetas.vue';
import JsBarcode from 'jsbarcode'


export default {
    name: 'Etiquetas',
    components: {
        Head,
    },
}
</script>

<script setup>

import { ref, onMounted, onUnmounted, computed, defineEmits } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js'; // Importación nombrada


//
// Reloj en tiempo real
//
const dia = ref('');
const mes = ref('');
const anio = ref('');
const hora = ref('');
const saludo = ref('');

function actualizarFechaHora() {
    const fecha = new Date();
    dia.value = fecha.getDate();

    const monthNamesClock = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    mes.value = monthNamesClock[fecha.getMonth()];
    anio.value = fecha.getFullYear();

    let horas = fecha.getHours();
    const minutos = fecha.getMinutes().toString().padStart(2, "0");
    const segundos = fecha.getSeconds().toString().padStart(2, "0");
    const periodo = horas >= 12 ? "pm" : "am";

    if (horas > 12) {
        horas -= 12;
    } else if (horas === 0) {
        horas = 12;
    }
    hora.value = `${horas}:${minutos}:${segundos} ${periodo}`;

    if (fecha.getHours() < 12) {
        saludo.value = "¡Buenos días";
    } else if (fecha.getHours() < 18) {
        saludo.value = "¡Buenas tardes";
    } else {
        saludo.value = "¡Buenas noches";
    }
}

let clockInterval = null;
onMounted(() => {
    actualizarFechaHora();
    clockInterval = setInterval(actualizarFechaHora, 1000);
});
onUnmounted(() => {
    clearInterval(clockInterval);
});

const page = usePage();
const currentRoute = computed(() => page.props.value.currentRoute);

const searchQuery = ref('');


const nombreEtiqueta = ref('')
const precioEtiqueta = ref('')
const showModal = ref(false)

// ✅ Mueve el array etiquetas al componente padre
const etiquetas = ref([])

// ✅ Generar ID único
const generarIDUnico = () => {
    return Math.floor(10000000000 + Math.random() * 90000000000).toString()
}

// ✅ Función para crear etiqueta
const crearEtiqueta = (data) => {
    const idUnico = generarIDUnico()

    const nuevaEtiqueta = {
        id: idUnico,
        nombreEtiqueta: data.nombreEtiqueta,
        precioEtiqueta: data.precioEtiqueta,
        fecha: new Date().toLocaleString('es-CO'),
    }

    etiquetas.value.push(nuevaEtiqueta)

    // ✅ Generar código de barras
    setTimeout(() => {
        JsBarcode(`#barcode-${idUnico}`, idUnico, {
            format: 'CODE128',
            displayValue: true,
            width: 4,
            height: 100,
            margin: 2,
        }).render()
    }, 100)
}

// ✅ Emitir desde el modal
const emitirEtiqueta = () => {
    if (!nombreEtiqueta.value || !precioEtiqueta.value) {
        alert('Completa todos los campos')
        return
    }

    crearEtiqueta({
        nombreEtiqueta: nombreEtiqueta.value,
        precioEtiqueta: precioEtiqueta.value,
    })

    // Resetea el formulario
    nombreEtiqueta.value = ''
    precioEtiqueta.value = ''
    showModal.value = false
}

// ✅ Eliminar etiqueta desde el padre
const eliminarEtiqueta = (id) => {
    etiquetas.value = etiquetas.value.filter((etiqueta) => etiqueta.id !== id)
}

// ✅ Función para validar y formatear números mientras escribes
const form = ref({
    precioEtiqueta: '',
})

const formatearNumero = (value, maxDigits = 16, min = 0, max = 999999999999999) => {
    if (value === null || value === '') return ''

    // ✅ Eliminar caracteres no numéricos
    let cleanValue = value.toString().replace(/[^\d]/g, '')

    // ✅ Limitar el número de dígitos
    if (cleanValue.length > maxDigits) {
        cleanValue = cleanValue.slice(0, maxDigits)
    }

    let numberValue = parseFloat(cleanValue)
    if (isNaN(numberValue)) return ''

    // ✅ Limitar dentro del rango permitido
    if (numberValue > max) numberValue = max
    if (numberValue < min) numberValue = min

    // ✅ Devolver el valor formateado como string
    return numberValue.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
}

</script>

<template>
    <div>

        <Head title="Gestión de etiquetado" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar />

            <main class="w-full h-[100%] px-[40px] py-[20px] bg-transparent">
                <SaludoOpciones />

                <!-- navegable -->
                <div class="options flex gap-1 items-center text-[14px]">
                    <a :href="route('essentials.admin.dashboard')" class="hover:text-essentials-secundary">
                        <p>Dashboard</p>
                    </a>
                    <span class="material-symbols-rounded text-[18px]">chevron_right</span>
                    <p class="font-bold">Etiquetado y clasificación</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Gestión de rótulos</h4>
                    <div class="input-buscador">
                        <input v-model="searchQuery" type="search" placeholder="Buscar producto..." class="" />
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>



                    <!-- Botón para abrir el modal -->
                    <button @click="showModal = true" class="btn-essentials">
                        Crear nuevo rótulo
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>



                </div>

                <GeneradorEtiquetas  :etiquetas="etiquetas || []" :eliminarEtiqueta="eliminarEtiqueta" />




            </main>



            <Modal :isOpen="showModal" titulo="Creación de un nuevo rótulo"
                descripcion="En este apartado podrás crear etiquetas facilmente con solo llenar unos campos, podras imprimirla y hasta escanearla directamente"
                confirmText="Confirmar creación" @close="showModal = false" @confirm="emitirEtiqueta">
                <form @submit.prevent="emitirEtiqueta">
                    <div class="mt-[5px] flex flex-col justify-between gap-3 w-full">
                        <div class="mt-[10px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Título etiqueta:</p>
                            <div class="input-insertar">
                                <input type="text" placeholder="Ponle un nombreEtiqueta a tu rótulo" v-model="nombreEtiqueta" />
                                <span class="material-symbols-rounded text-essentials-primary">format_italic</span>
                            </div>
                        </div>

                        <div class="mt-[5px] w-[100%]">
                            <p class="my-[5px] text-[14px]">Ingresa el precio final:</p>
                            <div class="input-insertar">
                                <input type="text" min="0" max="999999999999999"
                                 placeholder="Precio de tu producto" v-model="precioEtiqueta"
                                 @input="precioEtiqueta = formatearNumero(precioEtiqueta)"/>
                                <span class="material-symbols-rounded text-essentials-primary">pin  </span>
                            </div>
                        </div>
                    </div>
                </form>
            </Modal>


            <!-- ✅ Componentes de etiquetas -->
            <GeneradorEtiquetas @crearEtiqueta="crearEtiqueta" />





        </div>
    </div>
</template>
