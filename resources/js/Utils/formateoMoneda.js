export function useFormatoNumero() {
    // ✅ Función para formatear el número
    const formatearNumero = (value, min = 0, max = 999999999999999) => {
      if (!value) return ''
  
      // ✅ Eliminar caracteres no numéricos
      let numero = value.toString().replace(/[^\d]/g, '')
  
      // ✅ Convertir a número y validar rango
      let numberValue = parseFloat(numero)
      if (isNaN(numberValue)) numberValue = 0
      if (numberValue > max) numberValue = max
      if (numberValue < min) numberValue = min
  
      // ✅ Formatear con separadores de miles
      return numberValue.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      })
    }
  
    return {
      formatearNumero
    }
  }
  