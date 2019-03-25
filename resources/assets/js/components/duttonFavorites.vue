<template>
  <button @click="toggleFavorite" type="button" class="btn float-right btn-primary btn-sm" data-toggle="dropdown" >
    {{ lable }} to Favorites
  </button>
</template>
<script>
export default {
  name: 'button-favorites',
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
  },
  props: [
    'related'
  ],
  data(){
    return {
      add: true, 
      lable: (this.add)? 'Add' : 'Remove',    
      userRelated:{
        type: Object,
        default : ()=> this.related
      },
    }
  },
  methods: {
    toggleFavorite(){
      this.add = !this.add
    }
  }
}
</script>

