<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Head } from '@inertiajs/vue3';

import dayjs from 'dayjs'
import 'dayjs/locale/es'
import localizedFormat from 'dayjs/plugin/localizedFormat'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(localizedFormat)
dayjs.extend(relativeTime)
dayjs.locale('es')


const props = defineProps({
    auth: Object,
    auditorias: Array

});

const page = usePage();

const aplicacion = props.auth?.user?.tienda?.aplicacion?.nombre_app || 'Sin app';
const rol = props.auth.user.rol?.tipo_rol || 'Sin rol'; // Obtén el tipo de rol

const formatFecha = (fecha) => {
  if (!fecha) return 'Sin fecha'

  const fechaObj = dayjs(fecha)

  if (!fechaObj.isValid()) return 'Fecha inválida'

  return `${fechaObj.format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')} (${fechaObj.fromNow()})`
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
            return 'hover:bg-universal-naranja';
        case 'Essentials':
            return 'hover:bg-essentials-primary';
        case 'Machine':
            return 'hover:bg-machine-primary';
        case 'Shopper':
            return 'hover:bg-shopper-primary';
        case 'Smart':
            return 'hover:bg-smart-primary hover:text-mono-negro';
        default:
            return 'hover:bg-gray-300';
    }
});





const bgFocus = computed(() => bgColor[appName.value]);
const textFocus = computed(() => bgText[appName.value]);
const buttonFocus = computed(() => button[appName.value]);
const bg = computed(() => colores2[appName.value]);


const showModal = ref(false);

const searchQuery = ref('');

// Refs para los inputs y la imagen
const file = ref(null)
const fileName = ref('')
const imageUrl = ref('')

// Datos del formulario
const form = ref({
    plu: '',
    nombreProducto: '',
    descripcionProducto: '',
    stockProducto: '',
    cantidadProducto: '',
    unidadMedidaProducto: '',
    categoriaProducto: '',
    subcategoriaProducto: '',
    proveedorProducto: '',
    marcaProducto: '',
    precioNetoProducto: '',
    descuentoProducto: '',
    ivaProducto: '',
    loteProducto: '',
    rsaProducto: '',
    vencimientoProducto: ''
})

// ✅ Límite de caracteres para el nombre del producto
const maxCaracteres = 100


// ✅ Función para capitalizar cada palabra
const capitalizeWords = (str) => {
    return str
        .split(' ') // ✅ Separa las palabras por espacio
        .filter(word => word) // ✅ Elimina espacios extras entre palabras
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // ✅ Capitaliza cada palabra
        .join(' ') // ✅ Une las palabras con un espacio
}

// ✅ Función para capitalizar al salir del campo
const handleBlur = (field) => {
    if (typeof form.value[field] === 'string') {
        form.value[field] = capitalizeWords(form.value[field].trim())
    }
}

// ✅ Función para el conteo y límite de caracteres (permite espacios)
const handleInput = (event, field) => {
    let value = event.target.value

    // ✅ Permitir espacios y cortar si excede el límite de caracteres
    if (value.length > maxCaracteres) {
        form.value[field] = value.slice(0, maxCaracteres)
    } else {
        form.value[field] = value
    }
}

// ✅ Función para subir archivo
const handleImageUpload = (event) => {
    const selectedFile = event.target.files[0]

    if (!selectedFile) return

    const maxSize = 3 * 1024 * 1024 // 3MB
    const validExtensions = ['image/jpeg', 'image/png', 'image/gif']

    if (selectedFile.size > maxSize) {
        alert('El archivo es demasiado grande. Máximo 3MB.')
        file.value = null
        return
    }

    if (!validExtensions.includes(selectedFile.type)) {
        alert('Formato no permitido. Sube una imagen en JPG, PNG o GIF.')
        file.value = null
        return
    }

    const reader = new FileReader()
    reader.onload = () => {
        imageUrl.value = reader.result
    }
    reader.readAsDataURL(selectedFile)

    file.value = selectedFile
    fileName.value = selectedFile.name
}

// ✅ Función para cancelar archivo
const resetImage = () => {
    file.value = null
    fileName.value = ''
    imageUrl.value = ''
}


// ✅ Función para obtener valor numérico correctamente formateado
const obtenerNumero = (value) => {
    if (!value) return 0
    // ✅ Eliminar los puntos de formato y convertir a número
    let valor = value.replace(/\./g, '').replace(',', '.')
    return parseFloat(valor) || 0
}

// ✅ Computada para el precio final (actualiza en tiempo real)
const precioFinal = computed(() => {
    const precioNeto = obtenerNumero(form.value.precioNetoProducto)
    const descuento = obtenerNumero(form.value.descuentoProducto)
    const iva = obtenerNumero(form.value.ivaProducto)

    const precioConDescuento = precioNeto - descuento
    const precioFinal = precioConDescuento + (precioConDescuento * (iva / 100))

    // ✅ Formato en pesos colombianos
    return precioFinal.toLocaleString('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
})

// ✅ Función para validar y formatear números mientras escribes
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

// ✅ Actualizar automáticamente los valores para la computada
watch(form, () => {
    obtenerNumero(form.value.precioNetoProducto)
    obtenerNumero(form.value.descuentoProducto)
    obtenerNumero(form.value.ivaProducto)
}, { deep: true })
</script>

<template>

    <div class="parentOver flex justify-between gap-[60px] mt-4">
        <div class="w-[60%]">
            <div class="encabezado mb-5">
                <h2 class="text-blanco text-[25px] font-semibold">Creación de categorias</h2>
                <p class="mb-[10px] text-secundary-light">En este apartado podrás crear una nueva categoria a la sucursal
                    de tu tienda, recuerda llenar todos los campos correspondientes.</p>
            </div>

            <!-- 🖼️ Contenedor de carga de imagen -->
            <div class="container cursor-pointer flex justify-between items-center gap-4">
                <!-- Área de carga -->
                <div class="wrapper w-full h-full border border-dashed border-secundary-light rounded-lg flex justify-between items-center"
                    @click="$refs.fileInput.click()">
                    <div class="file-name p-2 flex justify-between w-full">
                        <p>
                            {{
                                fileName
                                    ? `${fileName} `
                                    : 'Imagen de referencia en formato PNG, JPG, JPEG no mayor a 3Mb'
                            }}
                        </p>
                        <span class="material-symbols-rounded">
                            upload
                        </span>
                    </div>
                </div>

                <!-- Botón de cancelar -->
                <div id="cancel-btn" @click="resetImage">
                    <i class="material-symbols-rounded rounded-md":class="['cursor-pointer', 'rounded-full', hoverClass]">
                        close
                    </i>
                </div>

                <!-- Input oculto para la carga de archivos -->
                <input ref="fileInput" type="file" hidden accept="image/png, image/jpeg, image/jpg"
                    @change="handleImageUpload" />
            </div>

            <!-- form -->
            <form action="" method="post" @submit.prevent="">
                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">PLU:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999" placeholder="Ponle identidad a tu item"
                                v-model="form.pluProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">pin</span>
                        </div>
                    </div>

                    <div class=" mt-[5px] w-[50%]">
                        <div class="contador-input flex items-center justify-between">
                            <p class="my-[5px] text-[16px]">Nombre:</p>
                            <p class="text-right text-[10px] text-gray-500">
                                {{ form.nombreProducto.length }} / {{ maxCaracteres }}
                            </p>
                        </div>

                        <div class="input-insertar w-full relative">
                            <input type="text" placeholder="Escribe su nombre" v-model="form.nombreProducto"
                                @input="handleInput($event, 'nombreProducto')" @blur="handleBlur('nombreProducto')"
                                name="nombreProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>

                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class="w-[50%] mt-[5px]">
                        <p class="my-[5px] text-[16px]">Categoría:</p>
                        <div class="custom-select ">
                            <select v-model="form.categoriaProducto" name="categoriaProducto">
                                <optgroup label="Selecciona una categoria">Categoria</optgroup>
                                <option value="sub1">Categoría 1</option>
                                <option value="sub2">Categoría 2</option>
                                <option value="sub3">Categoría 3</option>
                                <option value="sub4">Categoría 4</option>
                            </select>
                            <div class="select-arrow"></div>
                        </div>
                    </div>

                    <div class="w-[50%] mt-[5px]">
                        <p class="my-[5px] text-[16px]">Subcategoría:</p>
                        <div class="custom-select ">
                            <select v-model="form.subcategoriaProducto" name="subcategoriaProducto">
                                <optgroup label="Selecciona una subcategoria">Subcategoria</optgroup>
                                <option value="sub1">Subcategoría 1</option>
                                <option value="sub2">Subcategoría 2</option>
                                <option value="sub3">Subcategoría 3</option>
                                <option value="sub4">Subcategoría 4</option>
                            </select>
                            <div class="select-arrow"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <div class="contador-input flex items-center justify-between">
                            <p class="my-[5px] text-[16px]">Descripción:</p>
                            <p class="text-right text-[10px] text-gray-500">
                                {{ form.descripcionProducto.length }} / {{ maxCaracteres }}
                            </p>
                        </div>
                        <div class="input-insertar w-full">
                            <input type="text" placeholder="Describe tu producto" v-model="form.descripcionProducto"
                                @input="handleInput($event, 'descripcionProducto')"
                                @blur="handleBlur('descripcionProducto')" name="descripcionProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                        </div>
                    </div>

                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Cantidad en Stock:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999" placeholder="¿Cuantos tienes disponibles?"
                                :value="form.stockProducto" @input="validarNumero($event, 'stockProducto')"
                                name="stockProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">pin</span>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Cantidad:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999" placeholder="Ejemplo: 400"
                                :value="form.cantidadProducto" @input="validarNumero($event, 'cantidadProducto')"
                                name="cantidadProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">pin</span>
                        </div>
                    </div>

                    <div class="w-[50%] mt-[5px]">
                        <p class="my-[5px] text-[16px]">Unidad de medida:</p>
                        <div class="custom-select ">
                            <select v-model="form.unidadMedidaProducto" name="unidadMedidaProducto">
                                <optgroup label="Selecciona una subcategoria">Categoria</optgroup>
                                <option value="Unidades">Unidades</option>
                                <option value="Onzas">Onzas</option>
                                <option value="Gramos">Gramos</option>
                                <option value="Mililitros">Mililitros</option>
                                <option value="Litros">Litros</option>
                                <option value="Kilos">Kilos</option>
                                <option value="Libras">Libras</option>
                            </select>
                            <div class="select-arrow"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Marca:</p>
                        <div class="input-insertar w-full">
                            <input type="text" placeholder="Ejemplo: Coca cola" v-model="form.marcaProducto"
                                name="marcaProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                        </div>
                    </div>

                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Precio neto:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999" placeholder="Precio comprado" v
                                :value="form.precioNetoProducto" @input="validarNumero($event, 'precioNetoProducto')"
                                name="precioNetoProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Descuento:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999" placeholder="Escribe el monto"
                                :value="form.descuentoProducto" @input="validarNumero($event, 'descuentoProducto')"
                                name="descuentoProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">pin</span>
                        </div>
                    </div>


                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">IVA %:</p>
                        <div class="input-insertar w-full">
                            <input type="text" min="0" max="999999999999999"
                                placeholder="Si tiene ICUI E IVA ingresalo asi: (ICUI+IVA)" v-model="form.ivaProducto"
                                @input="formatearNumeroInput($event, 'ivaProducto')" name="ivaProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">pin</span>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Lote:</p>
                        <div class="input-insertar w-full">
                            <input type="text" placeholder="Ingresa Lote" v-model="form.loteProducto"
                                name="loteProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                        </div>
                    </div>

                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Registro Sanitario :</p>
                        <div class="input-insertar w-full">
                            <input type="text" placeholder="RSA de cada producto" v-model="form.rsaProducto"
                                name="rsaProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">format_italic</span>
                        </div>
                    </div>
                </div>

                <div class="mt-[5px] flex justify-between gap-3 w-full">
                    <div class=" mt-[5px] w-[50%]">
                        <p class="my-[5px] text-[16px]">Fecha vencimiento:</p>
                        <div class="input-insertar w-full">
                            <input type="date" placeholder="" v-model="form.vencimientoProducto"
                                name="vencimientoProducto">
                            <span class="material-symbols-rounded" :class="[textFocus]">event</span>
                        </div>
                    </div>

                    <div class="w-[50%] mt-[5px]">
                        <p class="my-[5px] text-[16px]">Proveedor:</p>
                        <div class="custom-select ">
                            <select v-model="form.proveedorProducto" name="proveedorProducto">
                                <optgroup label="Selecciona un proveedor">Proveedor</optgroup>
                                <option value="NA">No aplica</option>
                                <option value="Jhoann Zamudio">Jhoann Zamudio</option>

                            </select>
                            <div class="select-arrow"></div>
                        </div>
                    </div>
                </div>
                <button class=" mt-4 w-full" id="confirm-button" :class="[buttonFocus]">Confirmar creacion</button>
            </form>
        </div>

        <div class="w-[40%]  h-[80vh]">

            <p class="text-blanco text-[25px] font-semibold">Vista previa de tu categoria</p>

            <!-- 🔍 Vista previa de imagen -->
            <div class="image p-2 w-full flex justify-center items-center my-4" v-if="imageUrl">
                <img :src="imageUrl" alt="Vista previa"
                    class="border border-dashed border-secundary-light rounded-lg h-[400px] w-[400px] object-cover" />
            </div>



            <div class=" text-blanco  my-[15px]">
                <div class="flex justify-between items-center text-[25px] font-medium">
                    <p>{{ form.nombreProducto || 'Nombre' }}</p>
                    <p>{{ form.pluProducto || 'PLU' }}</p>
                </div>
                <p class="text-secundary-light text-4">{{ form.descripcionProducto || 'Descripción' }}</p>
            </div>

            <div class="text-blanco flex items-center justify-between text-[15px] my-2">
                <p class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-[15px]" :class="[textFocus]">
                        category
                    </span>
                    <span>{{ form.categoriaProducto || 'Categoría' }}</span>

                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">view_object_track</span>
                    <span>{{ form.subcategoriaProducto || 'Subcategoría' }}</span>

                </p>
                <p class="flex items-center gap-2 cantidad">{{ form.stockProducto || '0' }}<span
                        class="cantidad">Unidades</span></p>
            </div>

            <div class="text-blanco flex items-center justify-between text-[15px] my-2">
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">square_foot</span>
                    <span>{{ form.cantidadProducto || 'Unidad' }}</span><span>{{ form.unidadMedidaProducto || 'medida'
                        }}</span>

                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">person</span>
                    <span>{{ form.proveedorProducto || 'Proveedor' }}</span>
                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">verified</span>
                    <span>{{ form.marcaProducto || 'Marca' }}</span>
                </p>
            </div>

            <div class="text-blanco flex items-center justify-between text-[15px] my-2">
                <p class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-[15px]" :class="[textFocus]">
                        payments
                    </span>
                    <span>{{ form.precioNetoProducto || 'Precio neto' }}</span>
                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">remove</span>
                    <span>{{ form.descuentoProducto || 'Descuento' }}</span>
                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">percent</span>
                    <span>{{ form.ivaProducto || 'IVA + ICUI' }}</span>
                </p>
            </div>

            <div class="text-blanco flex items-center justify-between text-[15px] my-2">
                <p class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-[15px]" :class="[textFocus]">
                        table_convert
                    </span>
                    <span>{{ form.loteProducto || 'Lote' }}</span>
                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">news</span>
                    <span>{{ form.rsaProducto || 'Registro Sanitario' }}</span>
                </p>
                <p class="flex items-center gap-2"><span
                        class="material-symbols-rounded text-[15px]" :class="[textFocus]">event_busy</span>
                    <span>{{ form.vencimientoProducto || 'Vencimiento' }}</span>
                </p>
            </div>

            <div class=" flex justify-between items-center">
                <div class="estado-orden flex items-center gap-2 my-2">
                    <div class="gota h-5 w-5 bg-semaforo-verde shadow-verde rounded-full"></div>
                    <p>Activo</p>
                </div>
                <p class="flex items-center gap-2 text-[40px]">

                    <span>{{ precioFinal }} COP</span>
                </p>
            </div>
        </div>
    </div>
</template>