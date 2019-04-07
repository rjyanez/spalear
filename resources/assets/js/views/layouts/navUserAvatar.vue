<template>
<li class="nav-item dropdown ">
    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<div class="media align-items-center">
            <span class="avatar avatar-sm rounded-circle">
            	<img alt="Image placeholder" class="avatar-sm" :src="`/uploads/avatar/${currentUser.avatar}`">
            </span>
            <div class="media-body ml-2 d-none d-lg-block">
             	<span class="mb-0 text-sm  font-weight-bold">{{ currentUser.name }}</span>
            </div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
    	<nav-logout class="dropdown-item"/>
    </div>
</li>
</template>
<script>
import navLogout from './navLogout'

export default {
	components: {
		navLogout
	},
	name: "nav-user-avatar",
	computed: {
	    currentUser() {
	        return this.$store.getters.currentUser;
	    }
	},
}
</script>