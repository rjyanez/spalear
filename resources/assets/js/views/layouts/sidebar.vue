<template>
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand pt-0" href="#" role="button">
            <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" :src="`/uploads/avatar/${currentUser.avatar}`" class="w-100 h-100">
                </span>
                <div class="media-body ml-3 d-flex flex-column align-items-start w-25">
                    <a class="mb-0 d-block font-weight-bold">
                        {{ currentUser.name }}
                    </a>
                    <a href="#" class="text-sm text-muted" @click.prevent="logout">
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </a>
        <hr class="w-100">
        <!-- User -->
        <!-- <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="argon/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul> -->
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <!-- <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div> -->
            <!-- Form -->
            <!-- <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form> -->
            <!-- Navigation -->
            <ul v-if="show" ul class="navbar-nav">
                <li class="nav-item" v-for="link in links[show]" :key="link.name">
                    <router-link class="nav-link" :to="link.url">
                        <i :class="link.icon"></i> {{ link.title }}
                    </router-link>
                </li>
            </ul>
        </div>
    </div> 
</nav>
</template>
<script>
import {logout} from '../../helpers/auth'
import {sendGet} from '../../helpers/general'

export default {
  name: 'sidebar',
  data(){
    return {
      show: null,
      links: []
    }
  },
computed: {
    currentUser() {
        return this.$store.getters.currentUser;
    }
  },
  methods:{
      logout(){
        this.$store.dispatch('sendGet', { url:`/api/auth/logout`}).then((res) => {
            this.$store.commit('logout')
            this.$router.push('/login')
            this.$toasted.success(res.message)		           
        })
        .catch((error) => {
            this.$toasted.error('Something went wrong, please try again.')		         
        });
    },
    updateShow(val){
        let selected = null
        if(Object.keys(this.links).includes(val)) {
            return val
        } else{
            for (const module in this.links) {
               let codes = this.links[module].map((route)=>  route.code )
               if(codes.includes(val)){
                selected = module
                break
               } 
               
           }
        }
        return selected
    }
  },
  mounted(){
      this.$store.dispatch('sendGet', { url:`/api/function/secondary/${this.currentUser.rol_code}`, auth: true}).then(res => {
        if(res) this.links = JSON.parse(res.data.funtions)
        if((this.$router.currentRoute.name)) this.show = this.updateShow(this.$router.currentRoute.name)
    })
  },
  watch:{
    $route (to, from){        
        if (to.name) this.show = this.updateShow(to.name)
    }
 } 
}
</script>