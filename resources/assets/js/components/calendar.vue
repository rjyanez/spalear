<template lang="html">
  <div>
    <table class="calendar-table">
        <tr v-if="byShift">
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
        <tr v-if="!picker">
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
          <th v-if="shiftHourRange.length ===0 && byShift" :colspan="dayIndex.length + 1">
            the tutor does not have available hours on this shift
          </th>
          <template v-else>
            <th>
              <button  v-if="!picker" type="button" @click="current = new Date()" class="btn btn-link my-auto p-1">
                  <i class="fas fa-calendar-week"></i> TODAY                 
              </button>
            </th>
            <th v-for="(day, week) in dayIndex" >
              <a
                class="btn btn-link" 
                @click="(isEdit)? allDay(week) : ''"
                role="buttom"
                :class="{'text-orange': (String(new Date()).substring(0, 10) === String(day.value).substring(0, 10))}"
              >
                <template v-if="!picker">
                  <small class="d-block">{{day.label.month}}</small>
                  {{day.label.day}}
                  <small class="d-block">{{day.label.week}}</small>
                </template>
                <template v-else>
                  {{day.label.week}}
                </template>   
              </a>
            </th>
          </template>
        </tr>
        <template v-for="hour in shiftHourRange">
          <tr v-for="(min, i) in [00,30]" :key="`${hour}-${i}`" >
            <td rowspan="2" v-if="i==0" class="hour">
              <a style="cursor: pointer" @click="(isEdit)? allWeek(hour) : ''" role="buttom">
                {{ hour+':00' | hourFormat | timeConvert }}
              </a>
            </td> 
            <calendarEvent 
              v-for="(day, week) in dayIndex"
              :key="`${week}-${hour}-${min}`"
              :id="`${week}-${hour}-${min}`"
              :date="objectDatesFormater(day.value, week, hour, min)"
              :isSelected="isTimeSelectedDates(objectDatesFormater(day.value, week, hour, min))"
              :isAllowed="isTimeAllowedDates(objectDatesFormater(day.value, week, hour, min))"
              :isDlocked="isTimeDlockedDates(objectDatesFormater(day.value, week, hour, min))"
              @clickAddDay="(isEdit)? thisDay(...$event) : ''"
            >
              {{ hour+':'+min | hourFormat | timeConvert }}                                         
            </calendarEvent>
          </tr>
        </template>
    </table>
  </div>
</template>

<script>
import calendarEvent from './calendarEvent'
import {datesFrontendFormater} from './../helpers/general'

export default {
  components: {
    calendarEvent
  },
  name: 'calendar',
  props: {
    picker : false,
    minToday: false,
    isEdit: false,
    byShift: false,
    hours : {
      type: Array,
      default : ()=> [[0,24]]
    },
    min: {
      type: Number,
      default: 7
    },
    timeSelectedDates:{
      type: Array,
      default : ()=> ([]) 
    },
    timeAllowedDates : {
      type: Array,
      default : ()=> ([])        
    },
    timeDlockedDates : {
      type: Array,
      default : ()=> ([])        
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
      selectedDates: this.timeSelectedDates
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
      if(!this.byShift) {
        return this.hourRange
      } else {        
        return (this.shift === 'AM')? 
                this.hourRange.filter((hour)=> hour < 12) :
                this.hourRange.filter((hour)=> hour >= 12)
      }
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
    objectDatesFormater(date, week, hour, min){
      let val = []
      if(this.picker){
        val.push(`${hour}:${min}`)
        val.push(week)
      } else {
        let d = new Date(date)
        d.setHours(hour)
        d.setMinutes(min)
        val.push(d)
      }
      return datesFrontendFormater(...val)
    },
    isTimeSelectedDates(find){
      return (this.selectedDates.length === 0)? false : this.selectedDates.some((el) => (
        el.year === find.year &&  
        el.month === find.month && 
        el.week === find.week &&
        el.day === find.day && 
        el.hour === find.hour && 
        el.min === find.min
      ))
    },
    isTimeAllowedDates(find){
      return (this.timeAllowedDates.length === 0)? true : this.timeAllowedDates.some((el) => (
        el.week === find.week &&
        el.hour === find.hour && 
        el.min === find.min
      ))
    },
    isTimeDlockedDates(find){
      if(this.minToday){
        let current = new Date()
        if(find.date.getTime() <= current.getTime()) return true
      } 
      return (this.timeDlockedDates.length === 0)? false : this.timeDlockedDates.some((el) => (
        el.year === find.year &&  
        el.month === find.month && 
        el.week === find.week &&
        el.day === find.day && 
        el.hour === find.hour && 
        el.min === find.min
      ))
    },
    allDay(week){
      for(let i in this.hourRange){ 
        this.emmitTimeSelectedDate(
          document.getElementById(`${week}-${this.hourRange[i]}-0`), 
          this.objectDatesFormater(this.dayIndex[week]['value'], week, this.hourRange[i], 0)
        )
        this.emmitTimeSelectedDate(
          document.getElementById(`${week}-${this.hourRange[i]}-30`),
          this.objectDatesFormater(this.dayIndex[week]['value'], week, this.hourRange[i], 30)
        )
      }
    },
    allWeek(hour){
      for(let week in this.dayIndex){ 
        this.emmitTimeSelectedDate(
          document.getElementById(`${week}-${hour}-0`), 
          this.objectDatesFormater(this.dayIndex[week]['value'], week, hour, 0)
        )
        this.emmitTimeSelectedDate(
          document.getElementById(`${week}-${hour}-30`), 
          this.objectDatesFormater(this.dayIndex[week]['value'], week, hour, 30)
        )
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
      ]

      let weekDayNames = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ]

      let d = new Date(date)
      let day = d.getDate()
      let monthIndex = d.getMonth()
      let weekIndex = d.getDay()
      let year = d.getFullYear()
      let month = (type == "short") ? monthNames[monthIndex].slice(0, 3) : monthNames[monthIndex]
      let week = (type == "short") ? weekDayNames[weekIndex].slice(0, 3) : weekDayNames[weekIndex]
      return {
        label: { month, day, week },
        value: d
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
    thisDay(event, date) {
      this.emmitTimeSelectedDate(event.target, date)
    },
    emmitTimeSelectedDate(el, date){
      if(el && !el.classList.contains("disabled") && !el.classList.contains("booked")){
        el.classList.toggle("active");
        this.$emit("timeSelectedDate", date);
      }
    }

  },
  watch:{
    week(){
      this.setDays()
    },
  }
};
</script>