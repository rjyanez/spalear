<template>
<div class="media py-2 border-bottom card-hover">
  <img
    :src="`/uploads/avatar/${(isTeacher)? item.student.avatar : item.teacher.avatar}`"
    :title="(isTeacher)? item.student.name : item.teacher.name"
    class="mr-3 rounded-circle align-self-start"
    style="width: 5rem; height: 5rem;"
    data-toggle="tooltip"
    data-placement="top"
  >
  <div class="media-body">
    <div class="row">
       <div class="col text-left">
        <router-link
          class="mt-0 d-block"
          :to="(isTeacher)? `student/${item.student.id}` : `teacher/${item.teacher.id}`"
        >
          {{(isTeacher)? item.student.name : item.teacher.name}}
        </router-link>
        <small class="d-block text-muted" v-if="isTeacher">{{item.student.email}}</small>
        <p v-if="item.lesson" class="mt-1 mb-0">Lesson {{ item.lesson }} </p>
        <span :class="['badge', 'badge-pill', 'text-uppercase', `badge-${toLowerCase(item.type)}`]">
          {{item.type}}
        </span>
        <span v-if="item.level" :class="['badge', 'badge-pill', `badge-${toLowerCase(item.level)}`, 'text-uppercase']">
          {{item.level}}
        </span>
      </div>
      <div class="col text-right">
        <small class="text-muted">
          {{ `${date.year}/${date.month + 1}/${date.day}` }}
          <br>
          {{ date.hour+':'+date.min | hourFormat | timeConvert }}
        </small>      
        <div class="d-flex align-items-center justify-content-end my-2">
          <router-link
            :disabled="!active"
            :to="`meeting/${item.id}`"
            :class="{
                'icon icon-shape icon-sm btn btn-success rounded-circle': true,
                'disabled': !active 
            }"
          >
            <i class="far fa-play-circle"></i>
          </router-link>
          <buttonConfirmation
            v-if="isStudent"
            v-on:confirmation-success="meetingDelete"
            :messages="[
                { confirmation: false, icon: 'far fa-times-circle' , text: '', class: 'btn-outline-danger'},
                {
                  confirmation: true,
                  options: [
                    {icon: false , text: 'Yes', class: ' btn-outline-warning text-sm', accept: true},
                    {icon: false , text: 'Not', class: ' btn-warning text-sm', accept: false},                  
                  ]
                },
                { confirmation: false, icon: false , text: 'Ok!', class: 'btn-success text-sm'}                           
              ]"
            css="btn icon icon-shape icon-sm p-o"
          />
        </div>
      </div>
    </div>
  </div> 
 </div>
</template>
<script>
import { datesFrontendFormater } from './../../helpers/general';
import buttonConfirmation from './../../components/buttonConfirmation';

export default {
  name: "metting-card",
  components: {
    buttonConfirmation
  },
  props: {
    item: {
      type: Object,
      default: () => {}
    }
  },
  data(){
    return {
      now:  Date.now(),
      active: false
    }
  },
  computed: {
    date() {
      return datesFrontendFormater(this.item.date);
    },
    isTeacher(){
      return this.$store.getters.currentUser.id == this.item.teacher.id
    },
    isStudent(){
      return this.$store.getters.currentUser.id == this.item.student.id
    }
  },
  filters: {
    hourFormat(val) {
      let time = val.toString();
      if (time.length > 1) {
        time = time.split(":");
        for (const i in time) {
          time[i] = time[i].length > 1 ? time[i] : `0${time[i]}`;
        }
      }
      return time.join(":");
    },
    timeConvert(val) {
      let time = val.toString();
      if (time.length > 1) {
        time = time.split(":");
        time[time.length - 1] += time[0] < 12 ? " AM" : " PM";
        time[0] = +time[0] % 12 || 12;
      }
      return time.join(":");
    }
  },
  mounted () {
    setInterval(() => {
      this.now = Date.now()
    }, 5000)
  },
  methods: {
    toLowerCase(val){
      return val.toLowerCase()
    },
    meetingDelete() {
      this.$store
        .dispatch("sendDelete", {
          url: `/api/meeting/destroy/${this.item.id}`,
          data: {},
          auth: true
        })
        .then(res => {
          if (res) {
            this.$toasted.success(res.message);
            this.$emit("deleteMeeting");
          } else {
            this.$toasted.error("Something went wrong, please try again.");
          }
          this.loading = false;
        });
    }
  },
  watch: {
    now(){
      if(!this.active){
        let now = new Date(this.now)
        let date = new Date(this.date.date)
        if(now.getTime() >= date.getTime()) this.active = true
      }
    }
  }
};
</script>
