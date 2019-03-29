<template>
  <button 
    @click="toggleFavorite" 
    type="button" 
    class="btn btn-sm" 
    title="Favorite" 
  >
    <i :class="{'far':!favorite,'fas':favorite, 'fa-heart':true}"></i>
     {{ lable }} 
  </button>
</template>
<script>
export default {
  name: 'button-favorites',
  props: [
    'related', 'favorite', 'text'
  ],
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
    lable(){
      return (this.text === true)? (`${(this.favorite)? 'Remove' : 'Add'} Favorit`) : ''
    }
  },
  methods: {
    toggleFavorite(){
      // this.favorite = !this.favorite
    }
  },
}
</script>

