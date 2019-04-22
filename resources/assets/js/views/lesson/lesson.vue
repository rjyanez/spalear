<template>
<div>
    <header-lesson name="Users List"/>
    <div class="container mt--7">
      <div class="card shadow">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col">
              <h2> Level {{ route.title }}</h2>
            </div>
            <div class="col">
              <button class="mr-4 float-right btn btn-sm btn-info" @click="redirectTeachers">
                  <i class="fas fa-chalkboard-teacher"></i>
                  Schedule Class
              </button>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <lesson-list :list="list"/>
        </div>
      </div>
    </div>
</div>
</template>
<script>
import headerLesson from './header'
import lessonList from './list'

export default {
  components: {
    headerLesson,
    lessonList
  },
  data(){
    return {
      list: [],
      routesCodes : [
        {code: 'BAS', name: 'basic',title:'Basic'},
        {code: 'MED', name: 'medium',title:'Medium'},
        {code: 'ADV', name: 'advanced',title:'Advanced'},
      ]
    }
  },
  computed: {
    name(){
      return this.$route.params.code.toLowerCase()
    },
    route(){
      return this.routesCodes.filter(el => el.name === this.name)[0]
    }, 
  },
  mounted(){
    this.searchLesson()
  },
  methods: {
    searchLesson(){
      this.$store.dispatch('sendGet', { url:`/api/lesson/${this.route.code}`, auth: true}).then(res => {
        if(res.data.lessons) this.list = res.data.lessons
      })
    },
    redirectTeachers(){
      this.$toasted.info('To schedule a class you must first select a teacher.')
      this.$router.push({ path: `/teacher/all` });
    },
  },
  watch:{
    $route (to, from){
      this.searchLesson()
    }
  }
}
</script>
