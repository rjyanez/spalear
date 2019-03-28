<template>
	<div>
		<transition name="slide-fade">
			<sidebar v-if="isLoggedIn && sidebar"/>
		</transition>
		<div class="main-content">		
			<navbar>
				<template v-slot:toggle>
					<closeMenu @toggle="sidebar = !sidebar" :open="sidebar" />
				</template>
			</navbar>
			<router-view />
		</div>
	</div>
</template>
<script>
import closeMenu from './../../components/closeMenu'
import navbar from './navbar.vue';
import sidebar from './sidebar.vue'

export default {
    name: 'main-app',
    components: {navbar,sidebar,closeMenu},
    data(){
		return{
			sidebar: true
		}
    },
	computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }
	}
}
</script>