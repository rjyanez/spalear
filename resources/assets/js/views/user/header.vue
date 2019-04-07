<template>
  <div>    
    <header-auth background="profile-cover.jpg" :name="name"  :description="description"/>
  </div>
</template>
<script>
import headerAuth from '../layouts/headers/auth.vue'

export default {
  name: 'header-user',
  props: ['name','description'],
  components: {
    headerAuth
  }
}
</script>


