<template>
	<a href="#" @click.prevent="logout" role="button">
        <i class="fas fa-running"></i>
        Logout
    </a>	
</template>
<script>
export default {
	name: 'nav-logout',
	methods:{
		logout(){
		    this.$store.dispatch('sendGet', { url:`/api/auth/logout`}).then((res) => {
		        this.$store.commit('logout')
		        this.$router.push('/')
		        this.$toasted.success(res.message)		           
		    })
		    .catch((error) => {
		        this.$toasted.error('Something went wrong, please try again.')		         
		    });
		},
	}
}
</script>