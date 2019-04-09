<template>
  <div>
    <user-dashboard v-if="isRole('ST')"/>
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
  },
  methods: {
    isRole(role) {
      return this.$store.getters.isRole(role);
    },
  }
}
</script>

