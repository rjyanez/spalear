<template>
  <div>
    <!-- 		<transition name="slide-fade">
			<sidebar v-if="isLoggedIn && sidebar"/>
    </transition>-->
    <div class="main-content min-vh-100">
      <navbar>
        <!-- 				<template v-slot:toggle v-if="isLoggedIn">
					<closeMenu @toggle="sidebar = !sidebar" :open="sidebar" />
        </template>-->
      </navbar>
      <router-view/>
    </div>
  </div>
</template>
<script>
import closeMenu from "./../../components/closeMenu";
import navbar from "./navbar.vue";
import sidebar from "./sidebar.vue";

export default {
  name: "App",
  components: { navbar, sidebar, closeMenu },
  data() {
    return {
      sidebar: true
    };
	},
	mounted(){
		this.sessionExpired()
	},
  computed: {
		isLoggedIn() {
			return this.$store.getters.isLoggedIn;
		}	
	},
	methods: {
		sessionExpired(){
			setInterval( () => {
				if(this.isLoggedIn) {
					this.$store.dispatch('sessiosExpired').then((res) => {
								this.$store.commit('logout')
								this.$router.push('/')		           
								this.$toasted.error('Your session has expired.')		         
						})
						.catch((error) => {
						});
				}
			}, 3600000)
		}
	}
};
</script>