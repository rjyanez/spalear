<template>
  <div v-if="!isLoggedIn">
    <header-guest title="Sign In"/>
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" @submit.prevent="authenticate" ref="form">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input v-validate="'required|email'" v-model="email" class="form-control" :class="{'is-invalid' : errors.has('email')}" placeholder="Email" type="email" name="email" value="" required autofocus>
                                </div>
                                <span v-show="errors.has('email')"  class="invalid-feedback d-block" role="alert">
                                    <strong>{{ errors.first('email') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                    </div>
                                    <input v-validate="'required|min:6'" v-model="password" placeholder="Password" type="password" :class="{'form-control': true, 'is-invalid' : errors.has('password')}"  name="password" value="" required >            
                                </div>
                                <span v-show="errors.has('password')" class="invalid-feedback d-block" role="alert">
                                  <strong>{{ errors.first('password') }}</strong>
                                </span>
                            </div>
                            <div v-if="loading" class="text-center">
                                <button  class="d-block btn btn-outline-primary border-0 mt-4" type="button" disabled>
                                  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                  Loading...
                                </button>
                            </div>
                            <div class="text-center">                               
                                <button type="submit" :disabled="!isFormValid || loading" :class="{'btn btn-primary my-4': true, 'disabled': !isFormValid || loading }">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">                        
                            <a href="#" class="text-light">
                                <small>Forgot password?</small>
                            </a>
                    </div>
                    <div class="col-6 text-right">
                        <router-link to="/signup" class="text-light">
                            <small>Create new account</small>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  
</template>

<script>
import {formDataToJson} from '../../helpers/general'
import {login} from '../../helpers/auth'
import headerGuest from '../layouts/headers/guest'
export default {
    name: 'login',
    components: { headerGuest },
    data(){
      return {
        loading: false,
        email: null,
        password: null
      }
    },
    computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }
    },    
    methods: {
      authenticate(){
        
        this.loading = true;
         login(formDataToJson(this.$refs.form))
          .then((res) => {
		        this.$toasted.success(res.message)		    
                this.$store.commit("loginSuccess", res.data);
                this.loading = false;
                window.location.href = `${window.location.origin}/dashboard`
          })
          .catch((error) => {
		        this.$toasted.error('An error has occurred with these credentials, please check them.')
                this.loading = false;
          });
      },
    },
    computed: {
        isFormValid() {
        let valid =  Object.keys(this.fields).filter(key => this.fields[key].valid);
          return valid.length === Object.keys(this.fields).length ;
        },
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }	
    }
}
</script>