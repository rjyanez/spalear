<template>
<div class="d-flex justify-content-between align-items-center ranking" :class="'text-'+color" >
	<i 
		v-for="i in 5"
		:key="i"
		:class="{'far': i > points , 'fas': i <= points, 'fa-star': true}" 
		@click="toggleFavorite(i)"
		data-toggle="tooltip" 
		data-placement="top" 
		:title="i"
	></i>
</div>
</template>
<script>
import {addJsonToFormData} from './../helpers/general'

export default {
  name: 'stars',
  props: {
  	points:{
  		type: Number,
  		default: 0
		},
  	color:{
			type: String,
  		default: 'yellow'
  	},
		teacher: 0
	},
	computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    }
	},
  methods: {
    toggleFavorite(ranking){
      this.$store.dispatch("sendPost", { 
        url: `/api/teacher/ranking`, 
        data: addJsonToFormData({ 
          user: this.currentUser.id,
          teacher: this.teacher,
          ranking
        }), 
        auth: true 
      }).then(res => {
        if (res) {         
          this.$toasted.success(res.message);
          this.$emit('ranked')
        } else {
          this.$toasted.error("Something went wrong, please try again.");
        }
        this.loading = false;
      });
     
    }
  },	
}
</script>