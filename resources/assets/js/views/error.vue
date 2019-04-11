<template>
	<div class="header bg-gradient-default py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-2 pt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h1 class="text-white display-2">{{error.code}}</h1>
                        <h2 class="text-lead font-weight-lighter text-white">
                          <span class="font-weight-bold text-info">{{error.name}}</span> {{error.description}}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-primary" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mt-1 pb-5 text-center">
          <router-link  to="/dashboard" class="btn btn-primary">
            Home
          </router-link>
        </div>
    </div>
</template>
<script>
export default {
  data(){
    return {
      list: [
        { code : 403, name : 'Access Forbidden', description: `Sorry, you don't have access to this page.`},
        { code : 404, name : 'Page Not Found', description: `Sorry, page searched could not be found.`}
      ]
    }
  },
  computed: {
    error(){
      const code = parseInt(this.$router.currentRoute.params.code)
      const error = this.list.filter(el => el.code === code)
      return error[0]
    }
  }
}
</script>

