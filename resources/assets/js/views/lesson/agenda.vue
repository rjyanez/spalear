<template>
<ul class="list-unstyled">
	<li class="media my-3" v-for="(lesson, i) in list" :key="i">
		<img 
			:src="`/uploads/avatar/${lesson.teacher.avatar}`" 
			:title="lesson.teacher.name" 
			class="mr-3 rounded-circle"
			style="width: 5rem; height: 5rem;"
			data-toggle="tooltip" 
			data-placement="top"  
		>
		<div class="media-body text-left">
			<span class="mt-0 mb-1" v-if="lesson.type === 'GR' ">Lesson</span>
			<h4 class="heading">{{ lesson.type_name }}</h4>
			<span class="description">{{ lesson.date }}</span>
		</div>
	</li>
</ul>
</template>
<script>
import {datesFrontendFormater} from './../../helpers/general'

export default {
	name: 'agenda',
	props: {
		list: {
			type: Array,
			default: () => []
		}
	},
	mounted() {
		for (let i in this.list) {
			if(this.list[i].hasOwnProperty('date')) this.list[i]['date'] = datesFrontendFormater(this.list[i]['date'])
		}
	}
}
</script>