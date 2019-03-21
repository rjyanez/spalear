<template>
  <div>  
    <div class="row">
      <div class="col">
        <h6 class="heading-small text-muted mb-4">Teacher information</h6>
      </div>                
    </div>
    <div class="pl-lg-4">
      <calendar-picker @callback="selectTime($event)" :time_schedule="dates" :isEdit="isEdit"/>
    </div>
  </div> 
</template>
<script> 
import calendarPicker from '../../components/calendarPicker.vue'

export default {
  name: 'userTeacher',
  components: {
    calendarPicker
  },
  props: {
    isEdit : false,
    time_schedule: {
      type: Array,
      default : ()=> ({})        
    } 
  },
  data(){
    return {
      dates: this.time_schedule
    }
  },
  methods: {
    selectTime(event){    
      this.$emit("callback",event);
    }
  },
}
</script>

