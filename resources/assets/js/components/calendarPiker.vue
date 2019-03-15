<template>
  <div class="dropdown" :class="{'show': show}">
     <div class="dropdown-menu" :class="{'show': show}">

<div class="input-daterange datepicker row align-items-center my-auto">
    
        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                </div>
                <input class="form-control" placeholder="Start date" type="text" value="06/18/2018">
            </div>
        </div>
   
        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                </div>
                <input class="form-control" placeholder="End date" type="text" value="06/22/2018">
            </div>
        </div>
    
</div>      

     </div>
  </div>  
</template>
<script>
export default {
  name: 'calendarPiker',
  data(){
    return {
      show: true
    }
  },
  methods: {
    prevHour(){

    },
    nextHour(){

    }
  }
}
</script>

