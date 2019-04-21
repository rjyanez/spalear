<template>
  <div class="dropdown">
  <button 
    type="button"
    :class="['btn float-right btn-sm', currentSort.css]"
    data-toggle="dropdown"
    :title="currentSort.name"
  >
    <i :class="currentSort.icon"></i>
    <template v-if="text">{{currentSort.name}}</template>
  </button>
    <div class="dropdown-menu pt-2">
      <a 
        v-for="item in sortList.filter(el => el.key != currentSort.key)"
        class="dropdown-item"
        role="button"
        aria-pressed="true" 
        @click="toggleSort(item.key)" 
      >
        <i :class="[item.icon]"></i>
       {{item.name}}
      </a>
    </div>
  </div>
</template>

<script>
import {addJsonToFormData} from './../helpers/general'

export default {
  name: 'button-sort',
  props: {
    sort: {
      type: String, 
      default: 'NEU'
    },
    student:{
      type: Number, 
      default: 0
    },
    text:{
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      sortList:[
        { key: 'POS', name: 'Positive', css: 'btn-success', icon: 'fas fa-plus-square' },        
        { key: 'NEU', name: 'Neutral', css: 'btn-outline-success', icon: 'fas fa-square' }
      ]
    }
  },
  computed: {
    currentSort() {
      return this.sortList.filter(el=> el.key === this.sort)[0];
    }
  },
  methods: {
    toggleSort(value){
      this.$store.dispatch("sendPost", { 
        url: `/api/student/sort`, 
        data: addJsonToFormData({ 
          student: this.student,
          sort: value
        }), 
        auth: true 
      }).then(res => {
        if (res) {         
          this.$toasted.success(res.message);
          this.$emit('toggleSort')
        } else {
          this.$toasted.error("Something went wrong, please try again.");
        }
        this.loading = false;
      });
     
    }
  },
}
</script>

