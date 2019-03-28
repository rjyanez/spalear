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
        data-toggle="modal" 
        data-target="#timeScheduleModal"
      >
        <i class="fas fa-calendar-alt"></i>
      </button>
      <span 
        v-if="teacher.online"
        class="rounded-circle bg-success float-right status-porfile w-rem-1 h-rem-1" 
        title="Online"
      >
      </span>
    </div>
  </div>
  <div class="card-body pt-1 pb-3 h-100 text-center">
    <stars class="w-rem-8 mx-auto" :points="Math.round(teacher.ranking)" />
    <h3> {{ teacher.name }} </h3>
    <div class="h5">{{ teacher.country }} - {{ teacher.timeZone }}</div>
  </div>
</div>
</template>
<script>
import stars from './../../components/stars'
export default {
  name: 'teacher',
  props: ['teacher'],
  components: {
    stars
  }
}
</script>
