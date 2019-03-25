<template>
<ul class="list-unstyled">
	<li class="media" v-for="i in min">
		<img 
			src="list[i]['teacher']['avatar']" 
			title="list[i]['teacher']['name']" 
			class="mr-3"
			data-toggle="tooltip" 
			data-placement="top"  
		>
		<div class="media-body">
			{{list[i]}}
			<h5 class="mt-0 mb-1" v-if="list[i]['type']['code'] === 'GR' ">
				Lesson
			</h5>
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
		},
		min: {
	      type: Number,
	      default: 7
	    },
	},
	watch: {
		list(val){
			for (let i in this.list) {
				if(this.list[i].hasOwnProperty('date')) this.list[i]['date'] = datesFrontendFormater(this.list[i]['date'])
			}
		}
	}
}
</script>