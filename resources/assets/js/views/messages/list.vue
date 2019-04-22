<template>
  <div class="mx-6">
    <h3 class="text-center mb-3" v-if="list.length > 0">{{title}}</h3>
    <div class="overflow-auto max-vh-65">
      <ul class="list-unstyled mx-2">
        <message-card :item="item" v-for="(item, i) in limitedList" :key="i"/>
      </ul>
    </div>
    <div v-if="list.length > min" class="float-right mt-3">
      <button type="button" class="btn btn-secondary" @click="resetList">Reset</button>
      <button type="button" class="btn btn-primary" @click="incrementMin">Show More</button>
    </div>
  </div>
</template>
<script>
import messageCard from "./card";

export default {
  name: "messages-list",
  props: {
    list: {
      type: Array,
      default: () => []
    },
    min: {
      type: Number,
      default: 5
    },
    title: ""
  },
  data(){
    return {
      count: this.min
    }
  },
  components: {
    messageCard
  },
  computed: {
    limitedList() {
      return this.list.filter((el, i) => i < this.count);
    }
  },
  methods: {
    searchUser() {
      this.$emit("searchUser");
    },
    incrementMin(){
      this.count += this.min
    },
    resetList(){
      this.count = this.min
    }
  }
};
</script>
