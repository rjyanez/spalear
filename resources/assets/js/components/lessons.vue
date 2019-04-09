<template>
  <div class="accordion" :id="name">
  <div v-for="(item, index) in list" :key="index" :class="{'card':true, 'border border-success': index === select }">
    <div class="card-header p-1 " :id="`${name}-heading-${index}`">
      <div class="row">
        <div class="col text-left">
          <h5 class="card-title text-uppercase text-muted mb-0">
            <button 
              :class="{
                'btn btn-link ': true, 
                'text-dark':index !== select, 
                'text-success': index === select 
              }"
              type="button" 
              data-toggle="collapse" 
              :data-target="`#${name}-collapse-${index}`" 
              aria-expanded="false" 
              :aria-controls="`${name}-collapse-${index}`"
            >
              Lesson {{ item.name }}
            </button>
          </h5>            
        </div>
        <div class="col-auto" v-if="isSelect">
          <button 
            type="button"
            @click="setSelect(item,index)"
            :class="{
              'icon icon-shape rounded-circle btn': true, 
              'btn-outline-secondary':index !== select, 
              'btn-success': index === select 
            }"
          >
            <i class="far fa-check-circle"></i>
          </button>
        </div>
      </div>
    </div>

    <div :id="`${name}-collapse-${index}`" class="collapse" aria-labelledby="headingOne" :data-parent="`#${name}`">
      <div class="card-body text-left">
        {{ item.description }}
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: 'lessons',
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
    setSelect(item,index){
      this.select = index
      this.$emit('selectLesson',item)
    }
  }
}
</script>

