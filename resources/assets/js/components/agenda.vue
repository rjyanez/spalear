<template>
  <div>
    <div class="card card-hover border-top-0 border-left-0 border-right-0" v-for="(item, i) in list" :key="`${i}`">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <img
              :src="`/uploads/avatar/${item.avatar}`"
              :title="item.teacher"
              class="mr-3 rounded-circle align-self-start"
              style="width: 5rem; height: 5rem;"
              data-toggle="tooltip"
              data-placement="top"
            >
          </div>
          <div class="col d-flex flex-column align-items-start justify-content-center">
            <span class="text-muted">Teacher</span>
            <p class="h4">{{ item.teacher }}</p>
          </div>
          <div class="col d-flex flex-column align-items-start justify-content-center">
            <span class="text-muted">Type</span>
            <span
              :class="`badge badge-pill badge-${item.type.toLowerCase()} text-uppercase`"
            >{{item.type}}</span>
          </div>
          <div class="col d-flex flex-column align-items-start justify-content-center">
            <span class="text-muted">Lesson</span>
            <p class="h4">{{ item.lesson }}</p>
          </div>
					<div class="col d-flex flex-column align-items-start justify-content-center">
            <span class="text-muted">Level</span>
            <span
              :class="`badge badge-pill badge-${(item.level === 'N/A')? 'default': item.level.toLowerCase() } text-uppercase`"
            >{{item.level}}</span>
          </div>
          <div class="col d-flex flex-column align-items-start justify-content-center">
            <span class="text-muted">Date</span>
            <p
              class="h4 text-left"
            >
            {{ `${item.date.year}/${item.date.month}/${item.date.day}` }}
            <br/>  
            {{ item.date.hour+':'+item.date.min | hourFormat | timeConvert }}</p>
          </div>
					<div class="col d-flex flex-column align-items-start justify-content-center">
						<button 
							type="button"
							@click="'sfdsr'"
							:class="{
								'icon icon-shape btn btn-outline-danger rounded-circle': true, 
							}"
						>
            <i class="far fa-times-circle"></i>
          </button>
					</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { datesFrontendFormater } from "./../helpers/general";

export default {
  name: "agenda",
  props: {
    clases: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    list() {
      return this.clases.map(item => {
        return {
          avatar: item.teacher.avatar,
          teacher: item.teacher.name,
          type: item.type,
					lesson: item.lesson,
          level: item.level,					
          date: datesFrontendFormater(item.date)
        };
      });
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
  }
};
</script>