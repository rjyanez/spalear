<template>
  <div>
    <header-user name=""/>
    <div class="container-fluid mt--7">
      <div class="card card-profile shadow">
        <div class="px-4">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img :src="`/uploads/avatar/${user.avatar}`" class="rounded-circle" ref="img" style="width: 10rem; height: 10rem" />
                </a>
              </div>
            </div>
            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
              <!-- opciones de la derecha -->
            </div>
            <div class="col-lg-4 order-lg-1">
              <!-- info de la isquierda -->
            </div>
          </div>
          <div class="text-center mt-8">
            <h2>{{ user.name }}</h2>
            <div class="h4 font-weight-300">
              <i class="ni location_pin mr-2"> 
                {{ user. timeZone}} - {{ user.country }}
              </i>              
            </div>
            <div>
              <i class="ni education_hat mr-2">
                {{ user.description }}
              </i>
            </div>
          </div>
          <div class="mt-5 py-5 border-top text-center">
            <agenda :list="classList"/>
            
          </div>
        </div>
      </div>  
    </div>    
  </div>
</template>
<script>
import headerUser from './header'
import agenda from './../lesson/agenda'

export default {
  components: {
    headerUser,
    agenda
  },
  data(){
    return {
      user: {
        id: parseInt(this.$router.currentRoute.params.id) || this.$store.getters.currentUser.id
      },
      classList: [
        {
          date: "2019/03/24 8:30:00",
          type: {
            code: "CO",
            name: "Conversational",
          },
          teacher : {
            name: "Nombre del Profesor",
            avatar: "no-img.png",
          },
        },
        {
          date: "2019/03/24 8:30:00",
          type: {
            code: "GR",
            name: "Gramatical",
          },
          teacher : {
            name: "Nombre del Profesor",
            avatar: "no-img.png",
          }
        }
      ]      
    }
  },
  mounted(){
    this.$store.dispatch('sendGet', { url:`/api/user/${this.user.id}`, auth: true}).then(res => {
      if(res.data.user) this.user = res.data.user
    })
  }
}
</script>