<template>
  <li class="media my-4 ">
    <img
      :src="`/uploads/avatar/${item.from.avatar}`"
      :title="item.from.name"
      class="mr-3 rounded-circle align-self-start"
      style="width: 5rem; height: 5rem;"
      data-toggle="tooltip"
      data-placement="top"
    >
    <div class="media-body text-left border p-3 bg-secondary rounded">
      <router-link
        class="mt-0 mb-1 d-block"
        :to="`/teacher/${item.from.id}`"
      >
        {{item.from.name}}
      </router-link>
      <small class="text-muted d-block">
        {{ `${date.year}/${date.month + 1}/${date.day}` }}
        {{ date.hour+':'+date.min | hourFormat | timeConvert }}
      </small> 
      {{ item.message }}
    </div>
  </li>
</template>
<script>
import { datesFrontendFormater } from './../../helpers/general';

export default {
  name: "message-card",
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
      return datesFrontendFormater(this.item.created_at.date);
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
  methods: {
    toLowerCase(val){
      return val.toLowerCase()
    }
  }
};
</script>
