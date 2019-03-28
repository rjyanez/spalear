<template>
<div class="d-flex justify-content-between align-items-center" :class="'text-'+color" >
	<i 
		v-for="i in 5"
		:class="{'far': i >= points , 'fas': i < points, 'fa-star': true}" 
	></i>
</div>
</template>
<script>
export default {
  name: 'stars',
  props: {
  	points:{
  		type: Number,
  		default: 0
  	}
  	,
  	color:{
  		type: String,
  		default: 'yellow'
  	}
  }
}
</script>