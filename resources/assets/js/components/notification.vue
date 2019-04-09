<template>
<li class="nav-item dropdown notification" :class="{'full': (notifications.length != 0)}" >
    <a 
      class="nav-link pr-0" 
      href="#" role="button" 
      data-toggle="dropdown" 
      aria-haspopup="true" 
      aria-expanded="true" 
      ref="button" 
      @click="read = !read"
    >
      <span v-show="notifications.length > 0" class="text-center mark icon-sm bg-danger text-white rounded-circle shadow">
        <i>{{ notifications.length }}</i>
      </span>
      <i class="fas fa-bell fa-lg"></i>
    </a>
    <div  v-show="notifications.length > 0" class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
      <div class=" dropdown-header noti-title">
        <h6 class="text-overflow m-0">Notifications</h6>
      </div> 
      <a 
        v-for="(item, i) in notifications"
        :key="i"
        class="dropdown-item border-bottom" 
      >
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase mb-0">
              {{ item.data.subject }}
            </h5>
          </div>
          <div class="col-auto">
            <small> {{ item.created_at | dateDiff}}</small>
          </div>
        </div>
        <p class="mt-1 mb-0 text-muted text-sm">
          <small class="text-wrap">{{ item.data.message }}</small>
        </p>        
      </a>
      <a class="dropdown-item btn btn-link" href="#" role="button" @click="markAsRead">
        <h6 class="text-overflow m-0">Mark as read</h6>        
      </a>
    </div>
</li>
</template>
<script>
export default {
  name: "notification",
  data(){
    return {
      read: false,
      notifications: []
    }
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    }
  },
  mounted(){
    this.searchNotifications()
  },
  filters: {
    dateDiff(val){
      let now = new Date(),
      then = new Date(val),
      diff =    Math.trunc(now.getTime() - then.getTime()),
      days =    Math.round(Math.trunc(diff / 86400) / 1000),
      hours =   Math.round(Math.trunc(diff / 3600)  / 1000),
      minutes = Math.round(Math.trunc(diff / 60)  / 1000),
      seconds = Math.round(Math.trunc(diff) / 1000),
      value = `0s`;
      if(days > 0 )
      {
          value = `${days}d`
      } 
      else if(hours > 0) 
      {          
        value = `${hours}h`
      } 
      else if(minutes > 0)
      {
        value = `${minutes}m`
      } 
      else if(seconds > 0)
      {
        value = `${seconds}s`
      }

      return value;
    }
  },
  methods: {
    searchNotifications(){
      setInterval(() => {
        this.$store.dispatch('sendGet', { url:`/api/notification/unread/${this.currentUser.id}`, auth: true}).then(res => {
          if(res.data.notifications) this.notifications = res.data.notifications
        })
      }, 120000)
    },
    markAsRead(){
      this.$store.dispatch('sendGet', { url:`/api/notification/mark/${this.currentUser.id}`, auth: true})
      .then(res => {
        this.$toasted.success(res.message)
        this.notifications = []
      })

    }
  },
  watch: {
    read(to, from){
      if(!to && from && this.notifications.length > 0) this.markAsRead()
    }
  }
};
</script>

