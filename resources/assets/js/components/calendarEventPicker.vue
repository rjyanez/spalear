<template>
  <td 
    @click=" (isTimeSchedule)? ( (isBooked)? clickBooked() : clickAddDay($event) ) : '' "
    :class="{ 'active': isActive, 'disabled' : !isTimeSchedule, 'booked' : isBooked}"
    :data-toggle="(isBooked)? 'tooltip' : ''" 
    :data-html="(isBooked)? 'true' : ''" 
    :title="(isBooked)? isBooked.date : ''"
  >
    <slot></slot> 
  </td>
</template>
<script>
export default {
  props: [
    'isTimeSchedule',
    'isBooked',
    'isActive',
    'date',
    'hour',
    'min'
  ],
  methods: {
    clickBooked(){
      this.$emit("clickBooked", []);
    },
    clickAddDay(event){
      this.$emit("clickAddDay", [ event, this.date, this.hour, this.min ]);

    }
  }
  
}
</script>
              