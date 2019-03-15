<template lang="html">
  <div>
    <calendar-piker />
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
        <template v-for="(hour, keyHour) in dateRange.filter((obj, i)=>(i>4))">
          <tr v-for="(range, keyHourRange) in hour.range" :key="`${keyHour}-${keyHourRange}-am`">
            <td rowspan="2" v-if="keyHourRange==0" class="hour">{{ hour.label }} AM </td>
            <td 
              v-for="(day, keyHourRangeDay) in dayIndex" 
              :key="`${keyHour}-${keyHourRange}-${keyHourRangeDay}-am`"
              @click="slot($event, range, 'pm', day)"
            >              
                {{ range.start_time }}
            </td>
          </tr>
        </template>
        <template v-for="(hour, keyHour) in dateRange.filter((obj, i)=>(i<6))">
          <tr v-for="(range, keyHourRange) in hour.range" :key="`${keyHour}-${keyHourRange}-pm`">
            <td rowspan="2" v-if="keyHourRange==0" class="hour">{{ hour.label }} PM </td>
            <td 
              v-for="(day, keyHourRangeDay) in dayIndex" 
              :key="`${keyHour}-${keyHourRange}-${keyHourRangeDay}-pm`"
              @click="slot($event, range, 'pm', day)"
            >              
                {{ range.start_time }}
            </td>
          </tr>
        </template>
    </table>
  </div>
</template>

<script>
import calendarPiker from './calendarPiker'

export default {
  components: {
    calendarPiker
  },
  name: 'calendar',
  props: {
    min: {
      type: Number,
      default: 7
    }
  },
  watch: {},
  data() {
    return {
      dateRange: [
        {
          label: "01:00",
          range: [
            { start_time: "01:00:00", end_time: "01:30:00"},
            { start_time: "01:30:00", end_time: "02:00:00"}
          ]
        },
        {
          label: "02:00",
          range: [
            { start_time: "02:00:00", end_time: "02:30:00"},
            { start_time: "02:30:00", end_time: "03:00:00"}
          ]
          
        },
        {
          label: "03:00",
          range: [
            { start_time: "03:00:00", end_time: "03:30:00"},
            { start_time: "03:30:00", end_time: "04:00:00"}
          ]         
        },      
        {
          label: "04:00",
          range: [
            { start_time: "04:00:00", end_time: "04:30:00"},
            { start_time: "04:30:00", end_time: "05:00:00"}
          ]
        },
        {
          label: "05:00",
          range: [
            { start_time: "05:00:00", end_time: "05:30:00"},
            { start_time: "05:30:00", end_time: "06:00:00"}
          ] 
        },
        {
          label: "06:00",
          range: [
            { start_time: "06:00:00", end_time: "06:30:00"},
            { start_time: "06:30:00", end_time: "07:00:00"}
          ] 
        },
        {
          label: "07:00",
          range: [
            { start_time: "07:00:00", end_time: "07:30:00"},
            { start_time: "07:30:00", end_time: "08:00:00"}
          ]
        },
        {
          label: "08:00",
          range: [
            { start_time: "08:00:00", end_time: "08:30:00"},
            { start_time: "08:30:00", end_time: "09:00:00"}
          ]
        },
        {
          label: "09:00",
          range: [
            { start_time: "09:00:00", end_time: "09:30:00"},
            { start_time: "09:30:00", end_time: "10:00:00"}
          ]
        },
        {
          label: "10:00",
          range: [
            { start_time: "10:00:00", end_time: "10:30:00"},
            { start_time: "10:30:00", end_time: "11:00:00"}
          ]
        },
        {
          label: "11:00",
          range: [
            { start_time: "11:00:00", end_time: "11:30:00"},
            { start_time: "11:30:00", end_time: "12:00:00"}
          ]
        },
        {
          label: "12:00",
          range: [
            { start_time: "12:00:00", end_time: "12:30:00"},
            { start_time: "12:30:00", end_time: "01:00:00"}
          ]
        }
      ],
      dayIndex: [],      
      current: new Date(),
    };
  },
  computed: {
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
    slot(event, value, type, day) {
      event.target.classList.toggle("active");
      
      value.mode = type;
      value.date = day.value;
      this.$emit("callback", value);
    }
  },
  watch:{
    week(){
      this.setDays()
    }
  }
};
</script>

