<template>
    <div>
      <notifications />
      <slot />
    </div>
  </template>
  
  <script>
  import Notifications from './Components/DeteccionInternet/Notifications.vue';
  
  export default {
    components: {
      Notifications, 
    }
  }
  </script>
  