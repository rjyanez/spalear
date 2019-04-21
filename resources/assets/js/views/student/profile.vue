<template>
  <div>
    <header-student v-if="header" name=""/>
    <div class="container mt--7">
      <div class="card card-profile shadow">
        <div class="px-4">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img :src="`/uploads/avatar/${student.avatar}`" class="rounded-circle" ref="img" style="width: 10rem; height: 10rem" />
                </a>
              </div>              
            </div>
            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
              <div class="card-profile-actions py-4 mt-lg-0 d-flex align-items-center">
                <div class="card-profile-actions pb-4 mt-lg-0 d-flex align-items-center">
                  <template v-if="isCurrent">
                    <button  class="mr-4 float-right btn btn-sm btn-info" @click="redirectTeachers">
                      <i class="fas fa-chalkboard-teacher"></i>
                      Schedule Class
                    </button>
                    <router-link class="mr-4 float-right btn btn-sm btn-primary" to="teachers/favorite">
                      <i class="fas fa-star-half-alt"></i>
                      Favorite Teachers
                    </router-link>                    
                  </template>
                  <template v-if="!isCurrent && isRole('TE')">
                    <button-sort 
                      :student="student.id" 
                      :sort="student.sort"
                      @toggleSort="searchStudent"
                      class="mr-4 float-right" 
                      text
                    />
                    <button-level 
                      :student="student.id" 
                      :level="student.level"
                      @toggleLevel="searchStudent"
                      class="mr-4 float-right" 
                      text
                    />                       
                    <button-message :teacher="student.id" class="mr-4"/>
                  </template>
                </div>
              </div>
            </div>
            <div class="col-lg-4 order-lg-1">
              <div v-if="student.hasOwnProperty('progress')" class="card-profile-stats d-flex justify-content-center">
                <div>
                  <span class="heading">{{student.progress.conversational}}</span>
                  <span class="description">Conversational Lessons</span>
                </div>
                <div>
                  <span class="heading">{{student.progress.grammatical}}</span>
                  <span class="description">Grammatical Lessons</span>
                </div>
                <div>
                  <span class="heading">{{student.progress.teachers}}</span>
                  <span class="description">Teachers Distinct</span>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-3 pb-4  border-bottom  ">
            <h2>{{ student.name }}</h2>
            <div class="h4 font-weight-300">
              <i class="ni location_pin mr-2"> 
                {{ student. timeZone}} - {{ student.country }}
              </i>              
            </div>
            <div>
              <i class="ni education_hat mr-2">
                {{ student.description }}
              </i>
            </div>
          </div>
          <div class=" py-5 border-top text-center">
            <meetings-list @searchUser="searchStudent" title="Next Classes" :list="student.meetings" class="mx-5"/>
          </div>          
        </div>
      </div>  
    </div>    
  </div>
</template>
<script>
import headerStudent from './../user/header'
import buttonMessage from './../../components/buttonMessage'
import buttonSort from './../../components/buttonSort'
import buttonLevel from './../../components/buttonLevel'
import meetingsList from "./../meeting/list";

// import {formatDateToDataBase} from './../../helpers/general'

export default {
  props: {
    header: {
      type: Boolean,
      default: true
    }
  },
  components: {
    headerStudent,
    buttonMessage,
    buttonSort,
    buttonLevel,
    meetingsList
  },
  data(){
    return {
      student: {
        id: parseInt(this.$route.params.id) || this.$store.getters.currentUser.id
      }
    }
  },
  computed: {
    isCurrent(){
      return this.student.id == this.$store.getters.currentUser.id
    }
  },
  mounted(){
    this.searchStudent()
  },
  methods:{
    isRole(role) {
      return this.$store.getters.isRole(role);
    },
    redirectTeachers(){
      this.$toasted.info('To schedule a class you must first select a teacher.')
      this.$router.push({ path: `/teachers/all` });
    },
    searchStudent(){
      this.$store.dispatch('sendGet', { url:`/api/student/${this.student.id}`, auth: true}).then(res => {
        if(res.data.student) this.student = res.data.student
      })
    }
  }
}
</script>