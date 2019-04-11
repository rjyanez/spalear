<template>
<li class="nav-item dropdown ">
    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<i :class="link.icon" ></i>
		<span class="nav-link-inner--text">{{ link.title }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
			<template  v-for="child in link.childs" >
				<template v-if="allowedLink(child)">
					<router-link :key="child.code" :to="child.url" class="dropdown-item">
						<i :class="child.icon" ></i> {{ child.title }}
					</router-link >
				</template>   
				<template v-else >
					<a :key="child.code" href="#" role="buttom" class="dropdown-item text-muted">
						<i class="fas fa-lock"></i> {{ child.title }}
					</a>
				</template>           
			</template>
    </div>
</li>
</template>
<script>
export default {
	name: "nav-dropdown",
	props: ['link'],
	computed: {
		currentUser() {
			return this.$store.getters.currentUser;
		},
		exclusiveModules(){
			return [
				{
					module: 'lessons',
					rule: this.currentUser.meta_data.level,
					priorities: {
						'BAS':'lessons.basic',
						'MED':'lessons.medium',
						'ADV': 'lessons.advanced'
					},
				}
			]
		}
	},
	methods: {
		allowedLink(link){
			let exist = this.exclusiveModules.filter(el=> el.module === link.parent_name)	
			if(exist.length > 0){
				exist = exist[0]
				if(exist.rule in exist.priorities) {
					let priority = []
					for (const key in	exist.priorities) {
						priority.push(exist.priorities[key])
						if (key === exist.rule) {
								break
						} 
					}
					return priority.includes(link.code)
				}
			} 
			return true
		}
	}
}
</script>