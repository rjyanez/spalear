<template>
  <lesson-list :list="list"/>
</template>
<script>
import lessonList from './list'

export default {
  components: {
    lessonList
  },
  data(){
    return {
      list: [],
      routesCodes : {
        basic   : 'BAS',
        medium  : 'MED',
        advanced: 'ADV',
      }
    }
  },
  computed: {
    name(){
      return this.$router.currentRoute.params.code.toLowerCase()
    },
    code(){
      return (this.name in this.routesCodes)? this.routesCodes[this.name] : 'BAS'
    }, 
  },
  mounted(){
    this.searchLesson()
  },
  methods: {
    searchLesson(){
      this.$store.dispatch('sendGet', { url:`/api/lesson/${this.code}`, auth: true}).then(res => {
        if(res.data.lessons) this.list = res.data.lessons
      })

    }
  }
}
</script>
