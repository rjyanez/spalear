<template>
	<div>
		<sidebar v-if="isLoggedIn"/>
		<div class="main-content">		
			<navbar/>
			<router-view />
		</div>
	</div>
</template>
<script>
import navbar from './navbar.vue';
import sidebar from './sidebar.vue'

export default {
    name: 'main-app',
    components: {navbar,sidebar},
	computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }
	}
}
</script>