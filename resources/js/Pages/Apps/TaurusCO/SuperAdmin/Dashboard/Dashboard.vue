<template>
  <div>
    <h1>Bienvenido, {{ user.nombres_ct }} {{ user.apellidos_ct }}</h1>
    <p><strong>Estado:</strong> {{ user.estado?.tipo_estado || 'Sin estado' }}</p>
    <p><strong>Tipo de documento:</strong> {{ user.tipo_documento?.documento_legal || 'Sin tipo de documento' }}</p>
    <p><strong>Documento:</strong> {{ user.numero_documento_ct }}</p>
    <p><strong>Email:</strong> {{ user.email_ct }}</p>
    <p><strong>Teléfono:</strong> {{ user.telefono_ct }}</p>
    <p><strong>Rol:</strong> {{ user.rol?.tipo_rol || 'Sin rol' }}</p>

    <br>

    <h2>Datos de la tienda vinculada:</h2>
    <p><strong>Nombre de la tienda:</strong> {{ user.tienda?.nombre_tienda || 'Sin tienda' }}</p>
    <p><strong>Dirección:</strong> {{ user.tienda?.direccion_ct || 'Sin dirección' }}</p>
    <p><strong>Ciudad:</strong> {{ user.tienda?.barrio_ct || 'Sin ciudad' }}</p>
    <p><strong>Email de la tienda:</strong> {{ user.tienda?.email_tienda || 'Sin email' }}</p>
    <p><strong>Teléfono:</strong> {{ user.tienda?.telefono_ct }}</p>
    <p><strong>Fecha de creación:</strong> {{ formatFecha(user.fecha_creacion) }}</p>
    <p><strong>Fecha de modificación:</strong> {{ formatFecha(user.fecha_modificacion) }}</p>

    <br>
    
    <p><strong>Estado de la tienda:</strong> {{ user.tienda?.estado?.tipo_estado || 'Sin estado' }}</p>

    <br>

    <!-- ✅ Datos del plan -->
    <h2>Detalles del Plan:</h2>
    <p><strong>Sucursales:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_sucursales || 0 }}</p>
    <p><strong>Empleados:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_empleados || 0 }}</p>
    <p><strong>Proveedores:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_proveedores || 0 }}</p>
    <p><strong>Facturación electrónica:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_electronica || 0 }}</p>
    <p><strong>Facturación física:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_facturacion_fisica || 0 }}</p>
    <p><strong>Productos:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_productos || 0 }}</p>
    <p><strong>Servicios:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.cantidad_servicios || 0 }}</p>
    <p><strong>Manejo reservas:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_reservas || 'No aplica' }}</p>
    <p><strong>Manejo POS:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_pos || 'No aplica' }}</p>
    <p><strong>Manejo control de gastos:</strong> {{ user.tienda?.aplicacion?.plan?.detalles?.manejo_control_gastos || 'No aplica' }}</p>

    <br>

    <!-- ✅ Datos de la membresía -->
    <h2>Datos de la membresía:</h2>
    <p><strong>Nombre de la membresía:</strong> {{ user.tienda?.aplicacion?.membresia?.nombre_membresia || 'Sin membresía' }}</p>
    <p><strong>Precio:</strong> {{ user.tienda?.aplicacion?.membresia?.precio || 'Sin precio' }} USD</p>
    <p><strong>Periodo:</strong> {{ user.tienda?.aplicacion?.membresia?.periodo || 'Sin periodo' }}</p>
    <p><strong>Duración:</strong> {{ user.tienda?.aplicacion?.membresia?.duracion || 'Sin duración' }} días</p>
    <p><strong>Estado:</strong> {{ user.tienda?.aplicacion?.membresia?.estado?.tipo_estado || 'Sin estado' }}</p>
    
    <br>

    <button @click="logout">Cerrar sesión</button>
  </div>
</template>

<script setup>
import dayjs from 'dayjs'
import 'dayjs/locale/es' // ✅ Importa el idioma español

dayjs.locale('es')

import { router } from '@inertiajs/vue3'

const props = defineProps({
  auth: {
    type: Object,
    required: true
  }
})

const user = props.auth.user

const logout = () => {
  router.post(route('logout'))
}

const formatFecha = (fecha) => {
  if (!fecha) return 'Sin fecha'
  return dayjs(fecha).format('dddd D [de] MMMM [de] YYYY [a las] h:mm a')
}
</script>
