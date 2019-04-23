<template>
<div class="card border-0 card-profile mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-3 order-lg-2">
      <div class="card-profile-image">
        <router-link :to="`/student/${student.id}`">
          <img 
            class="card-img-top rounded-circle w-rem-7 h-rem-7" 
            :src="`/uploads/avatar/${student.avatar}`" 
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
      <button-sort
        :student="student.id"
        :sort="sort"
        @toggleSort="refreshStudent"
        class="float-left "
        css="icon icon-shape icon-sm p-o btn-sm p-0 float-left"
      />
      <button-level
        :student="student.id"
        :level="level"
        @toggleLevel="refreshStudent"
        class="float-right"
        css="icon icon-shape icon-sm btn-sm p-0 float-right mr-0"
      />
    </div> 
  </div>
  <div class="card-body pt-2 pb-3 h-100 text-center">
    <h3 class="my-0"> {{ student.name }} </h3>
    <small class="text-muted"> {{ student.email }} </small>
    <i>{{ student.country }} - {{ student.timeZone }}</i>
  </div>
</div>
</template>
<script>
import buttonSort from './../../components/buttonSort'
import buttonLevel from './../../components/buttonLevel'

export default {
  name: 'student',
  props: ['student','index'],
  components: {
    buttonSort,
    buttonLevel
  },
  computed:{
    sort(){
      return this.student.sort
    },
    level(){
      return this.student.level
    }
  },
  methods: {
    refreshStudent(){
      this.$emit('refreshStudent',this.index)
    }
  }
}
</script>
