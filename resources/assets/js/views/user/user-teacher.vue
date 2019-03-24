<template>
  <div>  
    <div class="row">
      <div class="col">
        <h6 class="heading-small text-muted mb-4">Teacher information</h6>
      </div>                
    </div>
    <div class="pl-lg-4">
      <calendar picker='true' @timeSelectedDate="timeSelectedDate($event)" :timeSelectedDates="timeSelectedDates" :isEdit="isEdit"/>
    </div>
  </div> 
</template>
<script> 
import calendar from '../../components/calendar.vue'

export default {
  name: 'userTeacher',
  components: {
    calendar
  },
  props: {
    isEdit : false,
    timeSchedule: {
      type: Array,
      default : ()=> ([])        
    } 
  },
  data(){
    return {
      timeSelectedDates: this.timeSchedule
    }
  },
  methods: {
    timeSelectedDate(event){    
      this.$emit("timeSelectedDate",event);
    }
  },
}
</script>

