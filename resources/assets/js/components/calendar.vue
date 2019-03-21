<template lang="html">
  <div>
    <table class="calendar-table">
        <tr>
          <th class="title" :colspan="min+1">
            <div>
              <button type="button" @click="prevWeek">
                <i class="ni ni-bold-left"></i>                 
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
                <i class="ni ni-bold-right"></i>                 
              </button>
            </div>
          </th>
        </tr>
        <tr>
          <th></th>
          <th v-for="(day, key) in dayIndex" >
            <a class="btn btn-link" @click="allDay(key)" role="buttom">
              <small class="d-block">{{day.label.month}}</small>
              {{day.label.day}}
              <small class="d-block">{{day.label.week}}</small>
            </a>
          </th>
        </tr>
        <template v-for="hour in hourRange">
          <tr v-for="(min, i) in [00,30]" :key="`${hour}-${i}`">
            <td rowspan="2" v-if="i==0" class="hour">
              <a style="cursor: pointer" @click="allWeek(hour)" role="buttom">
                {{ hour | hourFormat }} : 00 
              </a>
            </td>
            <td 
              v-for="(day, key) in dayIndex" 
              :key="`${key}-${hour}-${min}`"
              @click="thisDay($event, day, hour, min)"
              :class="{'active': isActive(day.value, key, hour, min)}"
              :id="`${key}-${hour}-${min}`"
            >              
              {{ hour | hourFormat }} : {{ min | hourFormat}}
            </td>
          </tr>
        </template>
    </table>
  </div>
</template>

<script>
// import calendarPiker from './calendarPiker'

export default {
  components: {
    // calendarPiker
  },
  name: 'calendar',
  props: {
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
    allDay(day){
      for(let i in this.hourRange){ 
        this.callback(document.getElementById(`${day}-${this.hourRange[i]}-0`), this.dayIndex[day]['value'], this.hourRange[i], 0)
        this.callback(document.getElementById(`${day}-${this.hourRange[i]}-3`), this.dayIndex[day]['value'], this.hourRange[i], 30)
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
      this.callback(event.target, day.value, hour, min)
    },
    callback(el, day, hour, min){
      el.classList.toggle("active");
      const date = new Date(day)
      let value = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
      this.$emit("callback", value);
    }

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

