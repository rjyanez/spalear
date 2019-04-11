<template>
  <div :class="{'card':true, 'border border-success': select }">
    <div class="card-header p-1 " :id="`${name}-heading-${index}`">
      <div class="row">
        <div class="col text-left">
          <h5 class="card-title text-uppercase text-muted mb-0">
            <button 
              :class="{
                'btn btn-link ': true, 
                'text-dark':!select, 
                'text-success': select 
              }"
              type="button" 
              data-toggle="collapse" 
              :data-target="`#${name}-collapse-${index}`" 
              aria-expanded="false" 
              :aria-controls="`${name}-collapse-${index}`"
              @click="expand = !expand"
            >
              Lesson {{ item.name }}
              <i v-if="expand" class="fas fa-angle-up"></i>
              <i v-else class="fas fa-angle-down"></i>
            </button>
          </h5>            
        </div>
        <div class="col-auto" >
          <button 
            v-if="isSelect"
            type="button"
            @click="setSelect"
            :class="{
              'icon icon-shape rounded-circle btn': true, 
              'btn-outline-secondary':!select, 
              'btn-success':select 
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
</template>
<script>
export default {
  name: 'lesson-card',
  props: {
    name: '',
    isSelect: {
      type: Boolean,
      default: false,
    },
    select: {
      type: Boolean,
      default: false,
    },
    index: 0,
    item: {}
  },
  methods: {
    setSelect(){
      this.$emit('setSelect',{ 'item': this.item,'index':this.index})
    }
  },
  data(){
    return {
      expand: {
        type: Boolean,
        default: false,
      }
    }
  }
}
</script>

