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
      return (this.text === 'true')? (`${(this.favorite)? 'Remove' : 'Add'} Favorite`) : ''
    }
  },
  methods: {
    toggleFavorite(){
      this.$store.dispatch("sendPost", { 
        url: `/api/teacher/favorite`, 
        data: addJsonToFormData({ 
          user: this.currentUser.id,
          teacher: this.teacher,
          favorite: !this.favorite
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

