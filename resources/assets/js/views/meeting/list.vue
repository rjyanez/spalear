<template>
  <div>
    <a href="#" v-if="list.length > 0" role="buttom">{{title}}</a>
    <meeting-card  @deleteMeeting="searchUser" :item="item" v-for="(item, i) in list" :key="i"/>
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