<template>  
  <modal @close="closeModalTime" :show="options.show" >
    <template v-slot:header>
      <div class="avatar-group mr-1 d-inline-block">
        <a href="#" class="avatar avatar-sm" data-toggle="tooltip" :data-original-title="options.teacher.name">
          <img :alt="options.teacher.name" :src="`/uploads/avatar/${options.teacher.avatar}`" class="rounded-circle avatar-sm">
        </a>
      </div>
      <h2 class="modal-title mt-2">{{options.teacher.name}} Time Schedule </h2>
    </template>
    <template v-slot:body>
        <calendar
          picker="true" 
          isEdit="false"
          :hours="modalHours"
          :timeAllowedDates="options.time" 
        />
    </template>
    <template v-slot:footer>
      <button type="button" class="btn btn-secondary" @click="closeModalTime">Close</button>
      <router-link class="btn btn-primary" :to="`/teachers/${options.teacher.id}`" >Profile</router-link>    
    </template>
  </modal>    
</template>
<script>

import modal from './../../components/modal'
import calendar from './../../components/calendar'

export default {
  name: 'modal-time-schedule',
  props: ['options'],
  components: {
    modal,
    calendar
  },
  computed:{
    modalHours(){
      if (this.options.time){
        let hours = [], values =this.options.time.map(el => el.hour)
        for (const key in values) {
          if(!hours.includes(values[key])) hours.push(values[key])
        }
        return hours 
      } else { 
        return [[0,24]] 
      }
    }
  },
  methods: {
    closeModalTime(){
      this.$emit("close")
    }
  }
}
</script>
