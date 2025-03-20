<template>
    <div>
      <form @submit.prevent="generarQR">
        <div class="mt-2">
          <label for="titulo">Título:</label>
          <input
            type="text"
            v-model="titulo"
            placeholder="Ponle un nombre al QR"
            class="border rounded p-2 w-full"
          />
        </div>
        <div class="mt-2">
          <label for="enlace">Enlace:</label>
          <input
            type="text"
            v-model="enlace"
            placeholder="https://example.com"
            class="border rounded p-2 w-full"
          />
        </div>
        <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded">
          Generar QR
        </button>
      </form>
  
      <!-- Renderiza el QR si ya fue generado -->
      <div v-if="codigo" class="mt-4">
        <div :id="`qrcode-${codigo}`" class="w-[200px] h-[200px]"></div>
        <div class="mt-2">
          <p class="font-semibold">{{ titulo }}</p>
          <p class="text-gray-500 text-sm">ID: {{ codigo }}</p>
          <a :href="enlace" target="_blank" class="text-blue-500 underline">
            Ir al enlace
          </a>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, nextTick } from 'vue'
  import QRCode from 'qrcode'
  
  const titulo = ref('')
  const enlace = ref('')
  const codigo = ref('')
  const idsGenerados = new Set()
  
  const emit = defineEmits(['guardarQR'])
  
  const generarIDUnico = () => {
    let id
    do {
      id = Math.floor(10000000000 + Math.random() * 90000000000).toString()
    } while (idsGenerados.has(id))
    idsGenerados.add(id)
    return id
  }
  
  const generarQR = async () => {
    if (!titulo.value || !enlace.value) {
      alert('Por favor, completa todos los campos.')
      return
    }
  
    // ✅ Formatear enlace correctamente
    const enlaceFormat = enlace.value.startsWith('http://') || enlace.value.startsWith('https://')
      ? enlace.value
      : `https://${enlace.value}`
  
    // ✅ Generar código único
    codigo.value = generarIDUnico()
  
    // ✅ Esperar a que el DOM se actualice
    await nextTick()
  
    // ✅ Generar el QR en el div correspondiente
    const canvas = document.createElement('canvas')
    const qrElement = document.getElementById(`qrcode-${codigo.value}`)
  
    if (qrElement) {
      qrElement.innerHTML = '' // Limpiar el contenedor si ya tiene un QR generado
      QRCode.toCanvas(canvas, enlaceFormat, {
        width: 200,
        height: 200,
        color: {
          dark: '#000000',
          light: '#ffffff'
        }
      }, (error) => {
        if (error) console.error('Error al generar QR:', error)
        else qrElement.appendChild(canvas) // Agregar el canvas generado
      })
    }
  
    // ✅ Emitir datos al componente padre
    emit('guardarQR', {
      titulo: titulo.value,
      enlace: enlaceFormat,
      codigo: codigo.value
    })
  }
  </script>
  