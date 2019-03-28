<template>
	<div class="menu-toggle" :class="{'open': open}" @click="toggle">
	  <div class="hamburger">
	    <span></span>
	    <span></span>
	    <span></span>
	  </div>
	  <div class="cross">
	    <span></span>
	    <span></span>
	  </div>
	</div>
</template>
<script>
export default {
	name: 'closeMenu',
	props: ['open'],
	methods: {
		toggle(){
			this.$emit("toggle");
		}		
	}

}
</script>