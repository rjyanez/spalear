<template>
  <td 
    @click=" (isAllowed && !isDlocked)? clickAddDay($event) : '' "
    :class="{ 'active': isSelected, 'disabled' : !isAllowed, 'booked' : isDlocked}"
  >
    <slot></slot> 
  </td>
</template>
<script>
export default {
  props: [
    'isSelected',
    'isAllowed',
    'isDlocked',
    'date'
  ],
  methods: {
    clickAddDay(event){
      this.$emit("clickAddDay", [ event, this.date]);
    }
  }
  
}
</script>
              