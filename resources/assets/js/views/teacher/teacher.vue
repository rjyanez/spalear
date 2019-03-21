<template>
  <div>
    <header-teacher name=""/>
    <div class="container-fluid mt--7">
      <div class="card card-profile shadow">
        <div class="px-4">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img :src="`/uploads/avatar/${teacher.avatar}`" class="rounded-circle" ref="img" style="width: 10rem; height: 10rem" />
                </a>
              </div>
            </div>
            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
              <!-- opciones de la derecha -->
            </div>
            <div class="col-lg-4 order-lg-1">
              <!-- info de la isquierda -->
            </div>
          </div>
          <div class="text-center mt-8">
            <h2>{{ teacher.name }}</h2>
            <div class="h4 font-weight-300">
              <i class="ni location_pin mr-2"> 
                {{ teacher. timeZone}} - {{ teacher.country }}
              </i>              
            </div>
            <div>
              <i class="ni education_hat mr-2">
                {{ teacher.description }}
              </i>
            </div>
          </div>
          <div class="mt-5 py-5 border-top text-center">
            <calendar 
              :hours="hours"           
            />
          </div>
        </div>
      </div>  
    </div>    
  </div>
</template>
<script>
import headerTeacher from './header'
import calendar from './../../components/calendar'

export default {
  components: {
    headerTeacher,
    calendar
  },
  data(){
    return {
      teacher: {
        id: this.$router.currentRoute.params.id
      }
    }
  },
  computed: {
    hours(){
      let hoursByDay = []
      for (const day in this.teacher.timeSchedule) {
        hoursByDay = hoursByDay.concat(this.teacher.timeSchedule[day])          
      }
      return [
        [Math.min(...hoursByDay),Math.max(...hoursByDay)]
      ]
    }
  },
  mounted(){
    this.$store.dispatch('sendGet', { url:`/api/teacher/${this.teacher.id}`, auth: true}).then(res => {
      if(res.data.teacher) this.teacher = res.data.teacher
    })
  }
}
</script>