<template lang="html">
  <div>
    <!-- <calendar-piker /> -->
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
          <th v-for="day in dayIndex" >
            <small class="d-block">{{day.label.month}}</small>
            {{day.label.day}}
            <small class="d-block">{{day.label.week}}</small>
          </th>
        </tr>
        <template v-for="hour in hourRange">
          <tr v-for="(min, i) in [00,30]" :key="`${hour}-${i}`">
            <td rowspan="2" v-if="i==0" class="hour">{{ hour | hourFormat }} : 00 </td>
            <td 
              v-for="(day, keyHourRangeDay) in dayIndex" 
              :key="`${hour}-${i}-${keyHourRangeDay}`"
              @click="slot($event, day, hour, min)"
              :class="{'active': isActive(day, hour, min)}"
              :title="(isActive(day, hour, min))"
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
      default : ()=> [[6,13]]
    },
    min: {
      type: Number,
      default: 7
    },
    dates : {
      type: Array,
      default : ()=> [
        {
          event : 'evento de hoy',
          date: new Date('2019','04','18','11','30')
        }
      ]
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
      current: new Date('2019','04','18','12','30'),
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
    isActive(day, hour, min = 0){
      let date = new Date(day)
      let val = {}
      let d1  = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
      for(let i in this.dates){
        let d2 = new Date(this.dates[i]['date']) 
        console.log(d1.getTime() === d2.getTime())
        if (d1.getTime() === d2.getTime()) val = this.dates[i]          
      }
      (val === {})? null: val        
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
    slot(event, day, hour, min = 0) {
      event.target.classList.toggle("active");
      let date = new Date(day)
      value = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, min, 0)
      
      this.$emit("callback", {event: '', date: value});
    }
  },
  watch:{
    week(){
      this.setDays()
    }
  }
};
</script>

