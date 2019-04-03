<template>
	<nav :class="{ 'navbar navbar-top navbar-expand-md navbar-dark' : true ,'navbar-horizontal' : isLoggedIn}">
	    <div :class="{'container px-4': !isLoggedIn, 'container-fluid' : isLoggedIn}" >
			<slot name="toggle"></slot>

			<!-- Brand -->
			<router-link  :to="(isLoggedIn)? '/dashboard': '/'" class="h2 mb-0 text-white text-uppercase d-none d-lg-inline-block navbar-brand">
				Spalear
			</router-link>

	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	            <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse" id="navbar-collapse-main">
	            <!-- Collapse header -->
	            <div class="navbar-collapse-header d-md-none">
	                <div class="row">
	                    <div class="col-6 collapse-brand">
	                        <router-link  :to="(isLoggedIn)? '/dashboard': '/'" class="h2 mb-0  text-uppercase">
								Spalear
							</router-link>
	                    </div>
	                    <div class="col-6 collapse-close">
	                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
	                            <span></span>
	                            <span></span>
	                        </button>
	                    </div>
	                </div>
	            </div>
	            <!-- Navbar items -->
	            <ul :class="{'navbar-nav ml-auto': true, 'align-items-center d-none d-md-flex': isLoggedIn}">
	                <li class="nav-item" v-for="link in links[(isLoggedIn)? 'auth' : 'guest' ]" :key="link.code">
	                    <router-link class="nav-link nav-link-icon" :to="link.url">
	                        <i :class="link.icon" ></i>
	                        <span class="nav-link-inner--text">{{ link.title }}</span>
	                    </router-link>
	                </li>
	            </ul>
	        </div>
	    </div>
	</nav>
</template>
<script>

export default {
	name: 'navbar',
	data(){
		return {
			links:{
				auth :[],
				guest : [
					{code:'register', title:'Register', url:'/signup',icon:'fas fa-user-circle'},
					{code:'login', title:'Login', url:'/login',icon:'fas fa-key'}
				]
			}
		}
	},
	computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        },        
	    currentUser() {
	        return this.$store.getters.currentUser;
	    }
	},
	mounted(){
		if(this.isLoggedIn) this.updateAuthLinks()
	},
	methods: {
		updateAuthLinks(){
			this.$store.dispatch('sendGet', { url:`/api/function/primary/${this.currentUser.rol_code}`, auth: true})
			.then(res => {
				if(res) this.links.auth = JSON.parse(res.data.funtions)        
			})
		}
	},
	watch: {
		isLoggedIn(val){
			if(val) this.updateAuthLinks()
		}
	}
}
</script>