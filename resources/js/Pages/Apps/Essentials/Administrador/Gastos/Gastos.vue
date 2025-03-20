<script>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar/Essentials/Sidebar.vue';
import SaludoOpciones from '@/Components/header/Essentials/SaludoOpciones.vue';
import ExportarExcel from '@/components/ExportarExcel/ExportarExcel.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';
import Gastos from '@/Components/TablaDatos/Essentials/Gastos.vue';




export default {
    name: 'InfoSucursales',
    components: {
        Head,
    },
}
</script>

<script setup>

import { ref, onMounted, onUnmounted, computed } from 'vue';
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

const showModal = ref(false);
const branchTitle = ref('');
const branchDescription = ref('');

const createBranch = () => {
    if (branchTitle.value && branchDescription.value) {
        console.log('Sucursal creada:', {
            title: branchTitle.value,
            description: branchDescription.value,
        });
        showModal.value = false; // Cierra el modal después de crear
    } else {
        alert('Por favor completa todos los campos.');
    }
};

const searchQuery = ref('');

// ✅ Función para validar y formatear números mientras escribes
const form = ref({
    monto: '',
})

const validarNumero = (event, field) => {
    let value = event.target.value

    // ✅ Eliminar caracteres no numéricos (solo números)
    value = value.replace(/[^\d]/g, '')

    // ✅ Limitar el valor a 16 dígitos
    if (value.length > 16) {
        value = value.slice(0, 16)
    }

    // ✅ Si el valor está vacío → establecer en 0
    if (value === '') {
        form.value[field] = ''
        return
    }

    // ✅ Limitar dentro de rango
    let numberValue = parseFloat(value)
    if (isNaN(numberValue)) {
        form.value[field] = ''
        return
    }

    if (numberValue > 999999999999999) numberValue = 999999999999999
    if (numberValue < 0) numberValue = 0

    // ✅ Asignar valor formateado automáticamente
    form.value[field] = numberValue.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
}
</script>

<template>
    <div>

        <Head title="Gestión de mis gastos" />


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
                    <p class="font-bold">Gestión de mis gastos</p>
                </div>

                <!-- header administrativo -->
                <div class="titulo-input-btn mt-4 flex justify-between items-center gap-5">
                    <h4 class="font-semibold text-[25px]">Control de gastos</h4>
                    <div class="input-buscador">
                        <input v-model="searchQuery" type="search" placeholder="Buscar producto..." class="" />
                        <span class="material-symbols-rounded text-essentials-primary">travel_explore</span>
                    </div>



                   <!-- Botón para abrir el modal -->
                   <button @click="showModal = true" class="btn-essentials">
                        Crear nueva sucursal
                        <span class="material-symbols-rounded"> outbound </span>
                    </button>



                </div>

                <div class="tarjetas-overviews my-7 flex w-full gap-2 justify-between">
            <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                <div class="metodoPago-monto">
                    <p class="text-[14px]">Imprevistos</p>
                    <p class="font-bold text-[25px]">$ 0,00</p>
                </div>
                <div
                    class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold">
                    1% <span class="material-symbols-rounded"> arrow_drop_up </span>
                </div>
            </div>
            <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                <div class="metodoPago-monto">
                    <p class="text-[14px]">Variables</p>
                    <p class="font-bold text-[25px]">$ 0,00</p>
                </div>
                <div
                    class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold">
                    1% <span class="material-symbols-rounded"> arrow_drop_up </span>
                </div>
            </div>
            <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                <div class="metodoPago-monto">
                    <p class="text-[14px]">Fijos</p>
                    <p class="font-bold text-[25px]">$ 0,00</p>
                </div>
                <div
                    class="contador bg-essentials-primary p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold">
                    1% <span class="material-symbols-rounded"> arrow_drop_up </span>
                </div>
            </div>
            <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                <div class="metodoPago-monto">
                    <p class="text-[14px]">Balance</p>
                    <p class="font-bold text-[25px]">Estable</p>
                </div>
                <div
                    class="contador bg-semaforo-verde shadow-verde p-2 rounded-md flex justify-center items-center w-10 h-5 font-bold">
                </div>
            </div>
        </div>

                <ExportarExcel idTabla="tabla" nombreArchivo="Movimientos_Almacenados"
                    titulo="Movimientos almacenados" />
                <!-- formulario -->
                <Gastos :searchQuery="searchQuery" />
            </main>

            <Modal :isOpen="showModal" titulo="Crear nuevo gasto" descripcion="EnEn este apartado podrás crear gastos de diferentes categorías, recuerda llenar los datos a conciencia para tener un registro exitoso." confirmText="Confirmar creación" @close="showModal = false"
                    @confirm="createBranch">
                     <!-- contenido del modal -->
            <div class="mt-[20px] flex justify-between gap-3 w-full">
                <div class="w-[50%]">
                    <p class="my-[5px] text-[14px]">Tipo de gasto:</p>
                    <div class="custom-select">
                        <select>
                            <option>Selecciona una categoría</option>
                            <option>Imprevisto</option>
                            <option>Variable</option>
                            <option>Fijo</option>
                        </select>
                        <div class="select-arrow text-blanco"></div>
                    </div>
                </div>

                <div class="w-[50%]">
                    <p class="my-[5px] text-[14px]">Monto total del gasto:</p>
                    <div
                        class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                        <span
                            class="material-symbols-rounded text-essentials-primary text-[20px] pl-[5px]">payments</span>

                        <input type="text" min="0" max="999999999999999"
                        :value="form.monto" @input="validarNumero($event, 'monto')"
                            class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro"
                            placeholder="Digita el monto en números." name="montoGasto" />
                    </div>
                </div>
            </div>

            <div class=" mt-[10px] w-[100%]">
                <p class="my-[5px] text-[14px]">Titulo de gasto:</p>
                <div
                    class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                    <span
                        class="material-symbols-rounded text-essentials-primary text-[20px] pl-[5px]">format_italic</span>

                    <input type="text" id="monto"
                        class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro"
                        placeholder="Ponle un titulo para el gasto." name="tituloGasto" />
                </div>
            </div>
            <div class=" mt-[10px] w-[100%]">
                <p class="my-[5px] text-[14px]">Descripción del gasto:</p>
                <div
                    class="w-[100%] transition-all rounded-[5px] border-[1px] border-secundary-light p-[3px] flex items-center gap-[8px]">
                    <span
                        class="material-symbols-rounded text-essentials-primary text-[20px] pl-[5px]">format_italic</span>

                    <input type="text" id="monto"
                        class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro "
                        placeholder="Describe brevemente este gasto." name="descripcionGasto" />
                </div>
            </div>

                </Modal>


        </div>
    </div>
</template>
