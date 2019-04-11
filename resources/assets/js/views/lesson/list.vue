<template>
  <div class="accordion" :id="name">
    <lesson-card  
      v-for="(item, index) in list" 
      :key="index" 
      :index="index" 
      :name="name"
      :isSelect="isSelect"
      :select="index === select"
      :item="item"
      @setSelect="setSelect($event)"
    />
</div>
</template>
<script>
import lessonCard from './card'
export default {
  name: 'lesson-list',
  components: {
    lessonCard
  },
  props: {
    name: {
      type: String,
      default: 'lessons'
    },
    list : {
      type: Array,
      default: () => []
    },
    isSelect: false,
  }, 
  data(){
    return {
      select: null,
    }
  },
  methods: {
    setSelect(event){
      this.select = event.index
      this.$emit('selectLesson',event.item)
    }
  }
}
</script>

