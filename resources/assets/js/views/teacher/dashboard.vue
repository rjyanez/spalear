<template>
  <div class="container  mb-3">
    <div class="card card-profile shadow">
      <div class="row">
        <div class="col">
          <div class=" py-5 border-right text-center">
            <meetings-list title="Your Next Classes" :list="teacher.meetings" class="mx-5"/>
          </div>
        </div>
        <div class="col"></div>
      </div>
    </div>  
  </div>    
</template>
<script>
import meetingsList from "./../meeting/list";

export default {
  components: {
    meetingsList
  },
  data() {
    return {
      teacher: {
        id:
          parseInt(this.$route.params.id) ||
          this.$store.getters.currentUser.id
      }
    };
  },
  mounted() {
    if (parseInt(this.$route.params.id) === this.$store.getters.currentUser.id) this.$router.push(`/dashboard`);
    this.searchTeacher();
  },
  computed: {
    show(){
			return (this.teacher.hasOwnProperty('roles'))? this.teacher.roles.includes('TE') : true
    }
  },
  methods: {
    searchTeacher() {
      console.log('lleho aqui')
      this.$store
        .dispatch("sendGet", { url: `/api/teacher/${this.teacher.id}/dashboard`, auth: true })
        .then(res => {
          if (res.data.teacher) {
            this.teacher = res.data.teacher;
          }
        });
    }
  }
};
</script>