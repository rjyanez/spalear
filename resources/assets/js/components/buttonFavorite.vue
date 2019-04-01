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
import {addJsonToFormData} from './../helpers/general'

export default {
  name: 'button-favorites',
  props: [
    'teacher', 'favorite', 'text'
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
      this.$store.dispatch("sendPost", { 
        url: `/api/teacher/list`, 
        data: addJsonToFormData({ 
          user: JSON.stringify(this.currentUser),
          teacher:  JSON.stringify(this.teacher)
        }), 
        auth: true 
      }).then(res => {
        if (res) {          
          this.$toasted.success(res.message);
          this.$emit('toggleFavorite')
        } else {
          this.$toasted.error("Something went wrong, please try again.");
        }
        this.loading = false;
      });
     
    }
  },
}
</script>

