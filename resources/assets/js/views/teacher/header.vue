<template>
  <div>    
    <header-auth background="teachers-cover.jpg" :name="name"  :description="description"/>
  </div>
</template>
<script>
import headerAuth from '../layouts/headers/auth.vue'

export default {
  name: 'header-teacher',
  props: ['name','description'],
  components: {
    headerAuth
  }
}
</script>


