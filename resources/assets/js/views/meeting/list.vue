<template>
  <div>
    <h3 class="text-center mb-3" v-if="list.length > 0">{{title}}</h3>
    <meeting-card @deleteMeeting="searchUser" :item="item" v-for="(item, i) in list" :key="i"/>
  </div>
</template>
<script>
import meetingCard from './card';

export default {
  name: "meetings-list",
  props: {
    list: {
      type: Array,
      default: () => []
    },
    title: ''
  },
  components: {
    meetingCard
  },
  methods: {
    searchUser(){
      this.$emit('searchUser')
    }
  }
};
</script>