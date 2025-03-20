import QRCode from 'qrcode'

const idsGenerados = new Set()

// ✅ Generar un ID único
const generarIDUnico = () => {
  let id
  do {
    id = Math.floor(10000000000 + Math.random() * 90000000000).toString()
  } while (idsGenerados.has(id))
  idsGenerados.add(id)
  return id
}

// ✅ Generar el código QR
const generarQR = async (elementId, enlace) => {
  if (!elementId || !enlace) return

  try {
    await QRCode.toCanvas(document.getElementById(elementId), enlace, {
      width: 400,
      height: 400,
      colorDark: '#000000',
      colorLight: '#ffffff',
      errorCorrectionLevel: 'H'
    })
  } catch (err) {
    console.error('Error generando el QR:', err)
  }
}

// ✅ Guardar el QR en el backend
const guardarQR = async (titulo, enlace, idUnico) => {
  try {
    const response = await fetch('http://localhost:3000/guardar', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ titulo, enlace, codigo: idUnico })
    })
    const data = await response.json()
    console.log('QR guardado:', data)
  } catch (error) {
    console.error('Error al guardar:', error)
  }
}

export { generarIDUnico, generarQR, guardarQR }
