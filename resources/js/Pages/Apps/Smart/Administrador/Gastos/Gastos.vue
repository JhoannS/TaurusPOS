<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import SaludoOpciones from '@/Components/header/SaludoOpciones.vue';
import ExportarExcel from '@/Components/ExportarExcel/ExportarExcel.vue';
import Modal from '@/Components/Modales/Essentials/Modal.vue';
import Gastos from '@/Components/TablaDatos/Essentials/Gastos.vue';


const props = defineProps({
    auth: Object,
});


const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol
const page = usePage();
const currentRoute = computed(() => page.props.value.currentRoute);
const showModal = ref(false);
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

// Definir los colores
const bgColor = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja',
    'Essentials': 'bg-essentials-primary shadow-essentials',
    'Machine': 'bg-machine-primary shadow-machine',
    'Shopper': 'bg-shopper-primary shadow-shopper',
    'Smart': 'bg-smart-primary shadow-smart text-mono-negro',
    'default': 'bg-gray-300 shadow-gray-300'
};

const button = {
    'TaurusCO': 'bg-universal-naranja shadow-universal-naranja rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Essentials': 'bg-essentials-primary shadow-essentials rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Machine': 'bg-machine-primary shadow-machine rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Shopper': 'bg-shopper-primary shadow-shopper rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'Smart': 'bg-smart-primary shadow-smart text-mono-negro rounded-[8px] py-[5px] px-[15px] flex items-center justify-center gap-2',
    'default': 'bg-gray-300 shadow-gray-300'
};

const bgText = {
    'TaurusCO': 'text-universal-naranja',
    'Essentials': 'text-essentials-primary',
    'Machine': 'text-machine-primary',
    'Shopper': 'text-shopper-primary',
    'Smart': 'text-smart-primary',
    'default': 'text-gray-300'
};
const colores2 = {
    'TaurusCO': 'bg-universal-naranja',
    'Essentials': 'bg-essentials-primary',
    'Machine': 'bg-machine-primary',
    'Shopper': 'bg-shopper-primary',
    'Smart': 'bg-smart-primary',
    'default': 'bg-gray-300 shadow-gray-300'
};

// Primero definimos appName para obtener el nombre de la app
const appName = computed(() => props.auth?.user?.tienda?.aplicacion?.nombre_app || 'default');

// Luego definimos hoverClass usando appName
const hoverClass = computed(() => {
    switch (appName.value) {
        case 'TaurusCO':
            return 'hover:border-universal-naranja ';
        case 'Essentials':
            return 'hover:border-essentials-primary ';
        case 'Machine':
            return 'hover:border-machine-primary ';
        case 'Shopper':
            return 'hover:border-shopper-primary ';
        case 'Smart':
            return 'hover:border-smart-primary ';
        default:
            return 'hover:border-gray-300';
    }
});

const bgFocus = computed(() => bgColor[appName.value]);
const textFocus = computed(() => bgText[appName.value]);
const buttonFocus = computed(() => button[appName.value]);
const bg = computed(() => colores2[appName.value]);
</script>

<template>
    <div>

        <Head title="Gestión de mis gastos" />


        <div class="bg-mono flex scrollbar-custom">
            <Sidebar :auth="auth" />

            <main class="w-full h-[100%] px-[40px] bg-transparent">
                <SaludoOpciones :auth="auth" />

                <!-- navegable -->
                <div class="options flex gap-1 my-3 items-center text-[14px]">
                    <a :href="route('aplicacion.dashboard', { aplicacion, rol })"
                        class="hover:text-essentials-secundary">
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
                        <span class="material-symbols-rounded" :class="[textFocus]">travel_explore</span>
                    </div>



                    <!-- Botón para abrir el modal -->
                    <button @click="showModal = true" class="" :class="[buttonFocus]">
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
                        <div class="contador p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold"
                            :class="[bgFocus]">
                            1% <span class="material-symbols-rounded"> arrow_drop_up </span>
                        </div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Variables</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div class="contador p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold"
                            :class="[bgFocus]">
                            1% <span class="material-symbols-rounded"> arrow_drop_up </span>
                        </div>
                    </div>
                    <div class="border border-secundary-light rounded-lg w-full p-2 flex justify-between items-center">
                        <div class="metodoPago-monto">
                            <p class="text-[14px]">Fijos</p>
                            <p class="font-bold text-[25px]">$ 0,00</p>
                        </div>
                        <div class="contador p-2 rounded-md flex justify-center items-center w-auto h-6 font-bold"
                            :class="[bgFocus]">
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

            <Modal :auth="auth" :isOpen="showModal" titulo="Crear nuevo gasto"
                descripcion="EnEn este apartado podrás crear gastos de diferentes categorías, recuerda llenar los datos a conciencia para tener un registro exitoso."
                confirmText="Confirmar creación" @close="showModal = false" @confirm="createBranch">
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
                                class="material-symbols-rounded text-[20px] pl-[5px]" :class="[textFocus]">payments</span>

                            <input type="text" min="0" max="999999999999999" :value="form.monto"
                                @input="validarNumero($event, 'monto')"
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
                            class="material-symbols-rounded text-[20px] pl-[5px]" :class="[textFocus]">format_italic</span>

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
                            class="material-symbols-rounded text-[20px] pl-[5px]" :class="[textFocus]">format_italic</span>

                        <input type="text" id="monto"
                            class="w-full focus:outline-none focus:border-none font-normal bg-mono-negro "
                            placeholder="Describe brevemente este gasto." name="descripcionGasto" />
                    </div>
                </div>

            </Modal>


        </div>
    </div>
</template>
