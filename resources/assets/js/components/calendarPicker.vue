<template lang="html">
  <div>
    <table class="calendar-table">
        <tr>
          <th></th>
          <th v-for="(day , value) in dayIndex">
            <a class="btn btn-link" @click="(isEdit)? allDay(value) : ''" role="buttom">
              {{day.label.week}}
            </a>
          </th>
        </tr>
        <tr v-for="(hour, i) in hourRange" :key="`${hour}-${i}`">
          <td class="hour">
            <a style="cursor: pointer" @click="(isEdit)? allWeek(hour) : ''" role="buttom">
              {{ hour | hourFormat }} : 00
            </a> 
          </td>
          <td 
            v-for="(day, keyHourRangeDay) in dayIndex" 
            :key="`${hour}-${i}-${keyHourRangeDay}`"
            @click="(isEdit)? slot($event, {day: keyHourRangeDay, hour}) : ''"
            :id="`${keyHourRangeDay}-${hour}`"
            :class="isActive(keyHourRangeDay, hour) ? 'active' : ''"
          >         
            {{ hour | hourFormat }} : 00
          </td>
        </tr>
    </table>
  </div>
</template>

<script>

export default {
  name: 'calendar-piker',
  props: {
    isEdit: false,
    hours : {
      type: Array,
      default : ()=> [[6,19]]
    },
    min: {
      type: Number,
      default: 7
    },
    time_schedule : {
      type: Array,
      default : ()=> ([])        
    }    
  },
  filters: {
    hourFormat(val){
      return (String(val).length === 1)? `0${val}` : String(val)
    }
  },
  data() {
    return {
      dayIndex: [],      
      current: new Date(),
      dates: this.time_schedule
    };
  },
  computed: {
    hourRange() {
      let values = []
      for( let key in this.hours){
        let start = this.hours[key][0], end = this.hours[key][1]
        for(let i = start; i < end; i++){
          values.push(i)
        }
      }
      return values
    },
    todaysDate(){
      return this.formatDate(new Date(this.current), "short")
    }, 
    week(){
      let date = new Date(this.current),
      startDate = date.getDate() - date.getDay() + 0,
      endDate = date.getDate() - date.getDay() + 6;
      return {
        from: this.formatDate(new Date(date.setDate(startDate)), "short"),
        to: this.formatDate(new Date(date.setDate(endDate)), "short")
      }
    }
  },
  mounted() {    
    this.setDays()
  },
  methods: {
    isActive(day,hour){
      let ele = document.getElementById(`${day}-${hour}`)
      if(this.dates.hasOwnProperty(day) &&  this.dates[day].includes(hour)){
        return true
      } else {
        if(ele && ele.classList.contains("active")) ele.classList.remove("active")
        return false
      }
    },
    allDay(day){
      for(let i in this.hourRange){ 
        document.getElementById(`${day}-${this.hourRange[i]}`).classList.toggle("active")
        this.$emit("callback", {day, hour: this.hourRange[i]})        
      }
    },
    allWeek(hour){
      for(let i in this.dayIndex){ 
        document.getElementById(`${i}-${hour}`).classList.toggle("active")
        this.$emit("callback", {day: i , hour})
      }
    },
    setDays(){
      this.dayIndex = []
      let dateIndex= 0
      for (var index = 0; index < this.min; index++) {
        this.dayIndex.push(
          this.formatDate(this.nextDate(this.week.from.value,dateIndex), "short")
        );
        dateIndex++;
      }
    },  
    formatDate(date, type) {
      let weekDayNames = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ],
      weekIndex = date.getDay(),
      week = (type == "short") ? weekDayNames[weekIndex].slice(0, 3) : weekDayNames[weekIndex];
      return {
        label: { week },
        value: date
      };
    },
    nextDate(date,index) {
      return new Date(new Date(date).getTime() + index * 24 * 60 * 60 * 1000);
    },
    slot(event, select) {
      event.target.classList.toggle("active");  
      this.$emit("callback", select);
    },
    
  },
  watch:{
    week(){
      this.setDays()
    },
    time_schedule(val){
      this.dates = {}
      this.dates = val
    }
  }
};
</script>

