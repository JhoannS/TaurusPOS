<template>
    <div class="encabezado flex justify-between items-center mb-3">
      <h2 class="text-blanco text-[20px] font-semibold">{{ titulo }}</h2>
      <span 
        class="material-symbols-rounded cursor-pointer" 
        @click="exportarExcel"
      >
        receipt_long
      </span>
    </div>
  </template>
  
  <script setup>
  import * as XLSX from 'xlsx'
  
  const props = defineProps({
    idTabla: {
      type: String,
      required: true
    },
    nombreArchivo: {
      type: String,
      default: 'Datos_Exportados'
    },
    titulo: {
      type: String,
      required: true
    }
  })
  
  const exportarExcel = () => {
    const tabla = document.getElementById(props.idTabla)
    if (!tabla) return alert('⚠️ Tabla no encontrada')
  
    // ✅ Crear hoja de trabajo desde la tabla
    const ws = XLSX.utils.table_to_sheet(tabla)
  
    // ✅ Crear un libro de trabajo
    const wb = XLSX.utils.book_new()
    XLSX.utils.book_append_sheet(wb, ws, 'Datos')
  
    // ✅ Descargar el archivo Excel
    XLSX.writeFile(wb, `${props.nombreArchivo}.xlsx`)
  }
  </script>
  
  <style scoped>
  span {
    @apply text-2xl text-gray-400 hover:text-essentials-primary transition-all;
  }
  </style>
  