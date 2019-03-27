<template>
<div class="card border-0 card-profile ">
  <div class="row justify-content-center">
    <div class="col-lg-3 order-lg-2">
      <div class="card-profile-image">
        <router-link :to="`/teachers/${teacher.id}`">
          <img class="card-img-top rounded-circle" :src="`/uploads/avatar/${teacher.avatar}`" ref="img" style="width: 7rem; height: 7rem">
        </router-link>                   
      </div>
    </div>
  </div>
  <div class="card-header text-center border-0 pb-0 pb-md-4">
    <div class="d-flex justify-content-center">
      <span 
        v-if="teacher.online"
        class="rounded-circle bg-success float-right ml-7 mt-4 status-porfile" 
        style="width: 1rem; height: 1rem;"
        title="Online"
      >
      </span>
    </div>
  </div>
  <div class="card-body pt-0 pt-md-4 h-100 text-center">
    <h3> {{ teacher.name }} </h3>
    <div class="h5">{{ teacher.country }} - {{ teacher.timeZone }}</div>
  </div>
</div>
</template>
<script>
export default {
  name: 'teacher',
  props: ['teacher']
}
</script>
