<template>
    <!-- Notificación sin internet -->
    <div v-if="mostrarNotificacion && tipoNotificacion === 'sinInternet'"
         class="notificacion translate-y-8 absolute w-[max-content] left-0 right-0 top-6 ml-auto mr-auto rounded-md bg-semaforo-rojo_opacity text-mono-blanco shadow-semaforo-verde">
      <div class="notificacion_body flex justify-center gap-3 items-center py-3 px-2">
        <div class="flex gap-2 items-center">
          <i class="material-symbols-rounded text-semaforo-rojo">cancel</i>
          <p>¡Sin conexión a Internet!</p>
        </div>
      </div>
      <div class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-rojo"></div>
    </div>
  
    <!-- Notificación conectado de nuevo -->
    <div v-if="mostrarNotificacion && tipoNotificacion === 'conectado'"
         class="notificacion translate-y-8 absolute w-[max-content] left-0 right-0 top-6 ml-auto mr-auto rounded-md bg-semaforo-verde_opacity text-mono-blanco shadow-semaforo-verde">
      <div class="notificacion_body flex justify-center gap-3 items-center py-3 px-2">
        <div class="flex gap-2 items-center">
          <i class="material-symbols-rounded text-semaforo-verde">check_circle</i>
          <p>¡Conectado nuevamente!</p>
        </div>
      </div>
      <div class="progreso_notificacion absolute left-1 bottom-1 h-1 scale-x-0 origin-left rounded-sm bg-semaforo-verde"></div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        mostrarNotificacion: false,
        tipoNotificacion: '',
        conectado: navigator.onLine, // 📌 Estado actual de la conexión
        intervalID: null, // 📌 Guardamos el ID del intervalo para detenerlo en beforeUnmount
      };
    },
    mounted() {
      window.addEventListener('show-notification', this.showNotification);
  
      // 📌 Verificar la conexión cada 5 segundos
      this.intervalID = setInterval(this.checkConnection, 5000);
    },
    beforeUnmount() {
      window.removeEventListener('show-notification', this.showNotification);
      
      // 📌 Eliminar el intervalo cuando el componente se desmonta
      if (this.intervalID) {
        clearInterval(this.intervalID);
      }
    },
    methods: {
      checkConnection() {
        const estaConectado = navigator.onLine;
        
        // 📌 Solo mostrar notificación si el estado cambia
        if (estaConectado !== this.conectado) {
          this.conectado = estaConectado;
          this.showNotification({ detail: estaConectado ? 'conectado' : 'sinInternet' });
        }
      },
      showNotification(event) {
        this.tipoNotificacion = event.detail;
        this.mostrarNotificacion = true;
  
        // 📌 Ocultar notificación después de 5 segundos
        setTimeout(() => {
          this.mostrarNotificacion = false;
        }, 5000);
      }
    }
  };
  </script>
  