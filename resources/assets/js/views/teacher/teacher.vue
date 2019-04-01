<template>
<div class="card border-0 card-profile mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-3 order-lg-2">
      <div class="card-profile-image">
        <router-link :to="`/teachers/${teacher.id}`">
          <img 
            class="card-img-top rounded-circle w-rem-7 h-rem-7" 
            :src="`/uploads/avatar/${teacher.avatar}`" 
            ref="img"
          >
        </router-link>                   
      </div>
    </div>
  </div>
  <div class="card-header text-center border-0 pb-0 pb-md-4">
    <div 
      class="d-flex justify-content-between align-items-center mt-2 mx-auto w-rem-7" 
    >
      <button 
        class="rounded-circle btn btn-primary btn-sm float-left status-porfile" 
        title="Time Schedule"
        type="button" 
        @click="showTeacherTimeSchedule"
      >
        <i class="fas fa-calendar-alt"></i>       
      </button>
      <button-favorite
        @toggleFavorite="refreshTeacher" 
        :teacher="teacher.id" 
        :favorite="favorite" 
        text="false"
        class="rounded-circle status-porfile btn-danger mb--5" 
      />
      <button-online-status 
        :online="online" 
        class="float-right status-porfile" 
      />
    </div>
  </div>
  <div class="card-body pt-3 pb-3 h-100 text-center">
    <stars 
      class="w-rem-8 mx-auto" 
      :points="teacher.ranking" 
      @ranked="refreshTeacher"
      :teacher="teacher.id" 
    />
    <h3 class="mb-0 mt-2"> {{ teacher.name }} </h3>
    <i>{{ teacher.country }} - {{ teacher.timeZone }}</i>
  </div>
</div>
</template>
<script>
import {formatDateToDataBase} from './../../helpers/general'
import buttonOnlineStatus from './../../components/buttonOnlineStatus'
import buttonFavorite from './../../components/buttonFavorite'
import stars from './../../components/stars'

export default {
  name: 'teacher',
  props: ['teacher','index'],
  components: {
    stars,
    buttonFavorite,
    buttonOnlineStatus
  },
  computed:{
    favorite(){
      return this.teacher.favorite
    },
    online(){
      return this.teacher.online
    }
  },
  methods: {
    showTeacherTimeSchedule(){
      let time = formatDateToDataBase(this.teacher.timeSchedule)
      this.$emit("showTeacherTimeSchedule", { 'teacher': this.teacher ,time})
    },
    refreshTeacher(){
      this.$emit('refreshTeacher',this.index)
    }
  }
}
</script>
