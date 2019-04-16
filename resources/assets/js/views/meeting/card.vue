<template>
  <div class="card card-hover border-top-0 border-left-0 border-right-0">
    <div class="card-body">
      <div class="row">
        <div class="col  d-flex flex-column align-items-start justify-content-center">
          <img
            :src="`/uploads/avatar/${item.user.avatar}`"
            :title="item.user.name"
            class="mr-3 rounded-circle align-self-start"
            style="width: 5rem; height: 5rem;"
            data-toggle="tooltip"
            data-placement="top"
          >
        </div>
        <div class="col d-flex flex-column align-items-start justify-content-center">
          <span class="text-muted">{{ item.relation.toUpperCase() }}</span>
          <p class="h4">{{ item.user.name }}</p>
        </div>
        <div class="col d-flex flex-column align-items-start justify-content-center">
          <span class="text-muted">Type</span>
          <span
            :class="`badge badge-pill badge-${item.type.toLowerCase()} text-uppercase`"
          >{{item.type}}</span>
        </div>
        <div class="col d-flex flex-column align-items-start justify-content-center">
          <span class="text-muted">Level</span>
          <span
            :class="`badge badge-pill badge-${(item.level === 'N/A')? 'default': item.level.toLowerCase() } text-uppercase`"
          >{{item.level}}</span>
        </div>
        <div class="col d-flex flex-column align-items-start justify-content-center">
          <span class="text-muted">Lesson</span>
          <p class="h4">{{ item.lesson }}</p>
        </div>
        <div class="col d-flex flex-column align-items-start justify-content-center">
          <span class="text-muted">Date</span>
          <p class="h4 text-left text-sm">
            {{ `${date.year}/${date.month}/${date.day}` }}
            <br />
            {{ date.hour+':'+date.min | hourFormat | timeConvert }}
          </p>
        </div>
        <div class="col d-flex align-items-center justify-content-end">
          <router-link
            :disabled="!active"
            :to="`meeting/${item.id}`"
            :class="{
								'icon icon-shape btn btn-success rounded-circle': true,
                'disabled': !active 
						}"
          >
            <i class="far fa-play-circle"></i>
          </router-link>
          <buttonConfirmation
            v-on:confirmation-success="meetingDelete"
            :messages="[
                {icon: 'far fa-times-circle' , text: '', class: 'btn-outline-danger'},
                {icon: false , text: 'Sure?', class: ' btn-outline-warning'},
                {icon: false , text: 'Ok!', class: 'btn-success'}                           
              ]"
            class="btn icon icon-shape rounded-circle"
          />
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
