<template lang="html">
  <div>
    <table class="calendar-table">
        <tr>
          <th class="title" :colspan="min+1">
            <div>
              <button type="button" @click="shift = 'AM'" :disabled="shift == 'AM'">
                <i class="far fa-sun"></i> AM                
              </button>
              <button type="button" @click="shift = 'PM'" :disabled="shift == 'PM'">
                <i class="far fa-moon"></i> PM                 
              </button>
            </div>
          </th>
        </tr>
        <tr>
          <th></th>
          <th v-for="(day , value) in dayIndex">
            <a class="btn btn-link" @click="(isEdit)? allDay(value) : ''" role="buttom">
              {{day.label.week}}
            </a>
          </th>
        </tr>
        <tr v-for="(hour, i) in shiftHourRange" :key="`${hour}-${i}`">
          <td class="hour">
            <a style="cursor: pointer" @click="(isEdit)? allWeek(hour) : ''" role="buttom">
              {{ hour+':00' | hourFormat | timeConvert }}
            </a> 
          </td>
          <td 
            v-for="(day, key) in dayIndex" 
            :key="`${key}-${hour}`"
            @click="(isEdit)? slot($event, {day: key, hour}) : ''"
            :id="`${key}-${hour}`"
            :class="isActive(key, hour) ? 'active' : ''"
          >         
            {{ hour+':00' | hourFormat | timeConvert }}
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
      default : ()=> [[0,24]]
    },
    min: {
      type: Number,
      default: 7
    },
    time_schedule : {
      type: Object,
      default : ()=> ({})        
    }    
  },
  filters: {
    hourFormat(val){
      let time = val.toString()
      if(time.length > 1){
        time = time.split(':')
        for(const i in time){
          time[i] = time[i].length > 1 ? time[i] : `0${time[i]}`
        }
      }
      return time.join(':')
    },
    timeConvert(val){
      let time = val.toString()
      if(time.length > 1){
        time = time.split(':')
        time[time.length - 1] += (time[0] < 12 ) ? ' AM' : ' PM'
        time[0] = +time[0] % 12 || 12
      }
      return time.join(':')
    }
  },
  data() {
    return {
      dayIndex: [],      
      current: new Date(),
      shift: 'AM',
      dates: this.time_schedule
    };
  },
  computed: {
    hourRange() {
      let values = []
      for( let key in this.hours){
        if(typeof this.hours[key] === 'array' || typeof this.hours[key] === 'object'){
          let start = parseInt(this.hours[key][0]), end = parseInt(this.hours[key][1])
          if( Number.isInteger(start) &&  Number.isInteger(end)){
            for(let i = start; i < end; i++){
              values.push(i)
            }
          }
        } else {
          values.push(this.hours[key])
        }
      }
      values.sort((a, b)=> a-b)
      return values
    },
    shiftHourRange(){
      return (this.shift === 'AM')? 
              this.hourRange.filter((hour)=> hour < 12) :
              this.hourRange.filter((hour)=> hour >= 12)
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

