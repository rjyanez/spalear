<template>
  <div>
    <user-dashboard/>
  </div>
</template>
<script>
import headerAuth from '../views/layouts/headers/auth.vue'
import userDashboard from './user/dashboard'

export default {
  name: 'dashboard',
  components: {
    headerAuth,
    userDashboard
  }
}
</script>

