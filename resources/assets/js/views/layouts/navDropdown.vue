<template>
<li class="nav-item dropdown ">
    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<i :class="link.icon" ></i>
		<span class="nav-link-inner--text">{{ link.title }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
		<router-link v-for="child in link.childs" :key="child.code" :to="child.url" class="dropdown-item">
			<i :class="child.icon" ></i> {{ child.title }}
		</router-link >              
    </div>
</li>
</template>
<script>
export default {
	name: "nav-dropdown",
	props: ['link']
}
</script>