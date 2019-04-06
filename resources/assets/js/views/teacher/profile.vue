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
              <div class="card-profile-actions py-4 mt-lg-0 d-flex align-items-center">
                <button-favorite 
                :teacher="teacher.id" 
                :favorite="teacher.favorite"
                @toggleFavorite="searchTeacher"
                class="mr-4 float-right btn-primary" 
                text="true"
                />
                <button-message :teacher="teacher.id" class="mr-4"/>
              </div>
            </div>
            <div class="col-lg-4 order-lg-1">
              <!-- info de la isquierda -->
            </div>
          </div>
          <div class="text-center mt-5 pb-4  border-bottom  ">
            <h2>{{ teacher.name }}</h2>
            <stars 
              class="w-rem-8 mx-auto my-3" 
              :points="teacher.ranking" 
              @ranked="searchTeacher"
              :teacher="teacher.id" 
            />
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
          <classes 
            :teacher="teacher" 
            class="mx-9 my-5"
            @scheduleClass="searchTeacher"
          />
        </div>
      </div>  
    </div>    
  </div>
</template>
<script>
import headerTeacher from './header'
import classes from './classes'
import buttonMessage from './../../components/buttonMessage'
import buttonFavorite from './../../components/buttonFavorite'
import stars from './../../components/stars'
import {formatDateToDataBase} from './../../helpers/general'

export default {
  components: {
    classes,
    headerTeacher,
    buttonFavorite,
    buttonMessage,
    stars
  },
  data(){
    return {
      teacher: {
        id: this.$router.currentRoute.params.id
      }
    }
  },
  mounted(){
    this.searchTeacher()
  },
  methods:{
    searchTeacher(){
      this.$store.dispatch('sendGet', { url:`/api/teacher/${this.teacher.id}`, auth: true}).then(res => {
        if(res.data.teacher) this.teacher = res.data.teacher
        if(res.data.teacher.timeSchedule) this.teacher.timeSchedule = formatDateToDataBase(res.data.teacher.timeSchedule)
        if(res.data.teacher.bookedDates)  this.teacher.bookedDates  = formatDateToDataBase(res.data.teacher.bookedDates)
      })
    }
  }
}
</script>