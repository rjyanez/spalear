<template>

    <div class="container mb-3">
      <div class="card card-profile shadow">
        <div class="px-4">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img
                    :src="`/uploads/avatar/${user.avatar}`"
                    class="rounded-circle"
                    ref="img"
                    style="width: 10rem; height: 10rem"
                  >
                </a>
              </div>
            </div>
            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
              <div class="card-profile-actions pb-4 mt-lg-0 d-flex align-items-center">
                <button class="mr-4 float-right btn btn-sm btn-info" @click="redirectTeachers">
                  <i class="fas fa-chalkboard-teacher"></i>
                  Schedule Class
                </button>
                <router-link class="mr-4 float-right btn btn-sm btn-primary" to="teachers/favorite">
                  <i class="fas fa-star-half-alt"></i>
                  Favorite Teachers
                </router-link>
              </div>
            </div>
            <div class="col-lg-4 order-lg-1">
              <div v-if="user.hasOwnProperty('progress')" class="card-profile-stats d-flex justify-content-center">
                <div>
                  <span class="heading">{{user.progress.conversational}}</span>
                  <span class="description">Conversational Lessons</span>
                </div>
                <div>
                  <span class="heading">{{user.progress.grammatical}}</span>
                  <span class="description">Grammatical Lessons</span>
                </div>
                <div>
                  <span class="heading">{{user.progress.teachers}}</span>
                  <span class="description">Teachers Distinct</span>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <h2>{{ user.name }}</h2>
            <div class="h4 font-weight-300">
              <i class="ni location_pin mr-2">{{ user. timeZone}} - {{ user.country }}</i>
            </div>
            <div>
              <i class="ni education_hat mr-2">{{ user.description }}</i>
            </div>
          </div>
          <div class=" py-5 border-top text-center">
            <meetings-list @searchUser="searchUser" title="Your Next Classes" :list="user.meetings" class="mx-5"/>
          </div>
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
      user: {
        id:
          parseInt(this.$router.currentRoute.params.id) ||
          this.$store.getters.currentUser.id
      }
    };
  },
  mounted() {
    if (parseInt(this.$router.currentRoute.params.id) === this.$store.getters.currentUser.id) this.$router.push(`/dashboard`);
    this.searchUser();
  },
  methods: {
    redirectTeachers(){
      this.$toasted.info('To schedule a class you must first select a teacher.')
      this.$router.push({ path: `/teachers/all` });
    },
    searchUser() {
      this.$store
        .dispatch("sendGet", { url: `/api/user/${this.user.id}`, auth: true })
        .then(res => {
          if (res.data.user) {
            this.user = res.data.user;
            this.searchUserProgress();
          }
        });
    },
    searchUserProgress() {
      this.$store
        .dispatch("sendGet", {
          url: `/api/user/${this.user.id}/progress`,
          auth: true
        })
        .then(res => {
          if (this.user) this.$set(this.user, 'progress', res.data.progress);
        });
    }
  }
};
</script>