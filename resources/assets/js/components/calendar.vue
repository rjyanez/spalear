<template>
  <!-- <div class="calendar">
    <div class="calendar-header">
      <i class="fa fa-fw fa-chevron-left" @click="subtractMonth"></i>
      <h4>{{month + ' - ' + year}}</h4>
      <i class="fa fa-fw fa-chevron-right" @click="addMonth"></i>
    </div>
    <ul class="weekdays">
      <li v-for="day in days" :key="day"></li>
    </ul>
    <ul class="dates">
      <li v-for="blank in firstDayOfMonth" :key="blank">&nbsp;</li>
      <li v-for="date in daysInMonth" :key="date" :class="{'current-day': date == initialDate &amp;&amp; month == initialMonth && year == initialYear}">
        <span></span>
       </li>
    </ul>
  </div> -->
  <div>
    
  </div>
</template>
<script>
import moment from 'moment'
export default {
  name: 'calendar', 
  data(){
    return {
      week: []
    }
  },
  computed: {
    currentDate: function () {
      var t = this;
      return t.dateContext.get('date');
    },    
  },
  mounted(){
    console.log(this.currentDate)
  },
  methods: {
    updateWeek(){
      let startOfWeek = moment().startOf('isoWeek'),
      endOfWeek = moment().endOf('isoWeek'),
      days = [];

      day = startOfWeek

      while (day <= endOfWeek) {
          days.push(day.toDate());
          day = day.clone().add(1, 'd');
      }
      this.days = days
    }
  }
}
// export default {
//   name: 'calendar',
//   data(){
//     return{
//       today: moment(),
//       dateContext: moment(),
//       days: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
//     }
//   },
//   computed: {
//     year: function () {
//       var t = this;
//       return t.dateContext.format('Y');
//     },
//     month: function () {
//       var t = this;
//       return t.dateContext.format('MMMM');
//     },
//     daysInMonth: function () {
//       var t = this;
//       return t.dateContext.daysInMonth();
//     },
//     currentDate: function () {
//       var t = this;
//       return t.dateContext.get('date');
//     },
//     firstDayOfMonth: function () {
//       var t = this;
//       var firstDay = moment(t.dateContext).subtract((t.currentDate - 1), 'days');
//       return firstDay.weekday();
//     },
//     initialDate: function () {
//       var t = this;
//       return t.today.get('date');
//     },
//     initialMonth: function () {
//       var t = this;
//       return t.today.format('MMMM');
//     },
//     initialYear: function () {
//       var t = this;
//       return t.today.format('Y');
//     }
//   },
//   methods: {
//     addMonth: function () {
//       var t = this;
//       t.dateContext = moment(t.dateContext).add(1, 'month');
//     },
//     subtractMonth: function () {
//       var t = this;
//       t.dateContext = moment(t.dateContext).subtract(1, 'month');
//     }
//   }
// }
</script>
