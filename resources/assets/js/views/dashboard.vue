<template>
  <div>
    <header-empty/>
  </div>
</template>
<script>
import headerEmpty from '../views/layouts/headers/empty.vue'
import calendar from '../components/calendar.vue'

export default {
  name: 'dashboard',
  components: {
    headerEmpty
  }
}
</script>

