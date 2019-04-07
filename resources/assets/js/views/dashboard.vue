<template>
  <div>
    <header-auth/>
  </div>
</template>
<script>
import headerAuth from '../views/layouts/headers/auth.vue'
import calendar from '../components/calendar.vue'

export default {
  name: 'dashboard',
  components: {
    headerAuth
  }
}
</script>

