<template>
  <div class="dashboard">
    <header-auth background="dashboard-cover.jpg" name=""  description=""/>
    <user-dashboard v-if="roles.includes('ST')"/>
    <teacher-dashboard  v-if="roles.includes('TE')"/>
  </div>
</template>
<script>
import headerAuth from '../views/layouts/headers/auth.vue'
import userDashboard from './user/dashboard'
import teacherDashboard from './teacher/dashboard'

export default {
  name: 'dashboard',
  components: {
    headerAuth,
    userDashboard,
    teacherDashboard
  },
  data() {
    return {
      roles: [],
      id: parseInt(this.$router.currentRoute.params.id) || this.$store.getters.currentUser.id
    };
  },
  mounted(){
    this.searchRoles()
  }, 
  methods: {
    searchRoles(){
      this.$store
      .dispatch("sendGet", { url: `/api/user/${this.id}/roles`, auth: true })
      .then(res => {
        if (res.data.roles)  this.roles = res.data.roles;
      });

    }
  }
}
</script>

