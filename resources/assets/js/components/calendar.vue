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
          <th class="title" :colspan="min+1">
            <div>
              <button type="button" @click="prevWeek">
                <i class="fas fa-chevron-left"></i>                 
              </button>
              <a>
                <small class="d-block">
                  {{week.from.label.week }}, {{week.from.label.day }} {{week.from.label.month }}
                </small>
                -
                <small class="d-block">
                  {{week.to.label.week }}, {{week.to.label.day }} {{week.to.label.month }}
                </small>               
              </a>
              <button type="button" role="button" @click="nextWeek">
                <i class="fas fa-chevron-right"></i>               
              </button>
            </div>
          </th>
        </tr>
        <tr>
          <th v-if="shiftHourRange.length ===0" :colspan="dayIndex.length + 1">
            the tutor does not have available hours on this shift
          </th>
          <template v-else>
            <th>
              <button type="button" @click="current = new Date()" class="btn btn-link my-auto p-1">
                  <i class="fas fa-calendar-week"></i> TODAY                 
              </button>
            </th>
            <th v-for="(day, key) in dayIndex" >
              <a
              class="btn btn-link" 
              @click="allDay(key)" 
              role="buttom"
              :class="{'text-orange': (String(new Date()).substring(0, 10) === String(day.value).substring(0, 10))}"
              >
                <small class="d-block">{{day.label.month}}</small>
                {{day.label.day}}
                <small class="d-block">{{day.label.week}}</small>
              </a>
            </th>
          </template>
        </tr>
        <template v-for="hour in shiftHourRange">
          <tr v-for="(min, i) in [00,30]" :key="`${hour}-${i}`" >
            <td rowspan="2" v-if="i==0" class="hour">
              <a style="cursor: pointer" @click="allWeek(hour)" role="buttom">
                {{ hour+':00' | hourFormat | timeConvert }}
              </a>
            </td> 
            <calendarEventPicker 
              v-for="(day, key) in dayIndex"
              :key="`${key}-${hour}-${min}`"
              :id="`${key}-${hour}-${min}`"
              :date="day.value"
              :hour="hour"
              :min="min"
              :isActive="isActive(day.value, key, hour, min)"
              :isTimeSchedule="isTimeSchedule(key, hour)"
              :isBooked="isBooked(day.value, key, hour, min)"
              @clickBooked="thisBookedDay($event)"
              @clickAddDay="thisDay(...$event)"
            >
              {{ hour+':'+min | hourFormat | timeConvert }}                                         
            </calendarEventPicker>
          </tr>
        </template>
    </table>
  </div>
</template>

<script>
import calendarEventPicker from './calendarEventPicker'

export default {
  components: {
    calendarEventPicker
  },
  name: 'calendar',
  props: {
    hours : {
      type: Array,
      default : ()=> [[0,24]]
    },
    min: {
      type: Number,
      default: 7
    },
    time_schedule:{
      type: Object,
      default : ()=> ({}) 
    },
    booked : {
      type: Array,
      default : ()=> ([])        
    },
    booking : {
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
      shift: 'AM',      
      current: new Date(),
      dates: this.booking
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
    isTimeSchedule(week, hour){
      if(this.time_schedule.hasOwnProperty(week)){
        if(this.time_schedule[week].includes(hour)) return true
      }
      return false
    },
    isActive(day, week, hour, min = 0){

      let date = new Date(day)
      date = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
     
      for (const event in this.dates) {
        const dateEvent = new Date(this.dates[event])
        if(dateEvent.getTime() === date.getTime()) return true
      }

      let ele = document.getElementById(`${week}-${hour}-${min}`)
      if(ele && ele.classList.contains("active")) ele.classList.remove("active")
      return false
           
    },
    isBooked(day, week, hour, min = 0){

      let date = new Date(day)
      date = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
     
      for (const event in this.booked) {
        const dateEvent = new Date(this.booked[event]['date'])
        if(dateEvent.getTime() === date.getTime()) return this.booked[event]
      }
      return false           
    },
    allDay(day){
      for(let i in this.hourRange){ 
        this.callback(document.getElementById(`${day}-${this.hourRange[i]}-0`), this.dayIndex[day]['value'], this.hourRange[i], 0)
        this.callback(document.getElementById(`${day}-${this.hourRange[i]}-30`), this.dayIndex[day]['value'], this.hourRange[i], 30)
      }
    },
    allWeek(hour){
      for(let i in this.dayIndex){ 
        this.callback(document.getElementById(`${i}-${hour}-0`), this.dayIndex[i]['value'], hour, 0)
        this.callback(document.getElementById(`${i}-${hour}-30`), this.dayIndex[i]['value'], hour, 30)
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
      let monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ],
      weekDayNames = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ],
      day = date.getDate(),
      monthIndex = date.getMonth(),
      weekIndex = date.getDay(),
      year = date.getFullYear(),
      month = (type == "short") ? monthNames[monthIndex].slice(0, 3) : monthNames[monthIndex],
      week = (type == "short") ? weekDayNames[weekIndex].slice(0, 3) : weekDayNames[weekIndex];
      return {
        label: { month, day, week },
        value: date
      };
    },
    nextDate(date,index) {
      return new Date(new Date(date).getTime() + index * 24 * 60 * 60 * 1000);
    },
    nextWeek(){
      this.current = this.nextDate(this.week.from.value,this.min)
    },
    prevWeek(){
      this.current = this.nextDate(this.week.from.value,-this.min)
    },
    thisDay(event, day, hour, min = 0) {
      this.callback(event.target, day, hour, min)
    },
    thisBookedDay(event){
      console.log('thisBookedDay')

    },
    callback(el, day, hour, min){
      if(el && !el.classList.contains("disabled") && !el.classList.contains("booked")){
        el.classList.toggle("active");
        const date = new Date(day)
        let value = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
        this.$emit("callback", value);
      }
    }

  },
  watch:{
    week(){
      this.setDays()
    },
    booked(val){
      this.dates = {}
      this.dates = val
    }
  }
};
</script>

