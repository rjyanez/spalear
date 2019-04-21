<template>
  <div class="dropdown">
  <button 
    type="button" 
    :class="['btn float-right btn-sm', currentLevel.css]"
    data-toggle="dropdown" 
    :title="currentLevel.name" 
  >
    <i :class="currentLevel.icon"></i>
    <template v-if="text">{{currentLevel.name}}</template>
  </button>
    <div class="dropdown-menu pt-2">
      <a 
        v-for="item in levelList.filter(el => el.key != currentLevel.key)"
        class="dropdown-item"
        role="button"
        aria-pressed="true" 
        @click="toggleLevel(item.key)" 
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
  name: 'button-level',
  props: {
    level: {
      type: String, 
      default: 'BAS'
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
      levelList:[
        { key: 'BAS', name: 'Basic', css:'btn-info', icon: 'far fa-star' },        
        { key: 'MED', name: 'Medium', css:'btn-primary', icon: 'fas fa-star-half' },
        { key: 'ADV', name: 'Advanced', css:'btn-warning', icon: 'fas fa-star' }
      ]
    }
  },
  computed: {
    currentLevel() {
      return this.levelList.filter(el=> el.key === this.level)[0];
    }
  },
  methods: {
    toggleLevel(value){
      this.$store.dispatch("sendPost", { 
        url: `/api/student/level`, 
        data: addJsonToFormData({ 
          student: this.student,
          level: value
        }), 
        auth: true 
      }).then(res => {
        if (res) {         
          this.$toasted.success(res.message);
          this.$emit('toggleLevel')
        } else {
          this.$toasted.error("Something went wrong, please try again.");
        }
        this.loading = false;
      });
     
    }
  },
}
</script>

