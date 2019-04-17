<template>
  <div class="container mb-3">
    <div class="card card-profile shadow">
      <div class="row">
        <div class="col pr-0">
          <div class=" py-5 text-center">
            <meetings-list title="Your Next Classes" :list="teacher.meetings" class="mx-5"/>
          </div>
        </div>
        <div class="col pl-0">
          <calendar
            minToday="true"
            class="m-2 mr-4 py-5"
            :byShift="(hours.length > 5)"
            :hours="hours"
            :timeDlockedDates="bookedDates"
            :timeAllowedDates="timeSchedule"     
          />          
        </div>
      </div>
    </div>  
  </div>    
</template>
<script>
import meetingsList from "./../meeting/list";
import calendar from './../../components/calendar'
import {formatDateToDataBase} from './../../helpers/general'

export default {
  components: {
    meetingsList,
    calendar
  },
  data() {
    return {
      teacher: {
        id:
          parseInt(this.$route.params.id) ||
          this.$store.getters.currentUser.id
      }
    };
  },
  mounted() {
    if (parseInt(this.$route.params.id) === this.$store.getters.currentUser.id) this.$router.push(`/dashboard`);
    this.searchTeacher();
  },
  computed: {
    hours(){
      if (this.timeSchedule){
        let hours = [], values =this.timeSchedule.map(el => el.hour)
        for (const key in values) {
          if(!hours.includes(values[key])) hours.push(values[key])
        }
        return hours 
      } else { 
        return [[0,24]] 
      }
    },
    timeSchedule(){
      return formatDateToDataBase(this.teacher.timeSchedule)
    }, 
    bookedDates(){
      return formatDateToDataBase(this.teacher.bookedDates)
    } 
  },
  methods: {
    searchTeacher() {
      console.log('lleho aqui')
      this.$store
        .dispatch("sendGet", { url: `/api/teacher/${this.teacher.id}/dashboard`, auth: true })
        .then(res => {
          if (res.data.teacher) {
            this.teacher = res.data.teacher;
          }
        });
    }
  }
};
</script>