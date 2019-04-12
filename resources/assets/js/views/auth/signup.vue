<template>
  <div>
    <header-guest title="Sign Up"/>
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" @submit.prevent="authenticate" ref="form">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                    </div>
                                    <input v-validate="'required|alpha_spaces'" v-model="name" class="form-control" :class="{'is-invalid' : errors.has('name')}" placeholder="Fullname" type="text" name="name" value="" required autofocus>
                                </div>
                                <span v-show="errors.has('name')"  class="invalid-feedback d-block" role="alert">
                                    <strong>{{ errors.first('name') }}</strong>
                                </span>
                            </div>                            
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input v-validate="'required|email|unique'" v-model="email" class="form-control" :class="{'is-invalid' : errors.has('email')}" placeholder="Email" type="email" name="email" value="" required>
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
                                    <input v-validate="'required|min:6'" v-model="password" placeholder="Password" type="password" :class="{'form-control': true, 'is-invalid' : errors.has('password')}"  name="password" value="" required ref="password">           
                                </div>
                                <span v-show="errors.has('password')" class="invalid-feedback d-block" role="alert">
                                  <strong>{{ errors.first('password') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                    </div>
                                    <input v-validate="'required|min:6|confirmed:password'" v-model="password_confirmation" placeholder="Confirm Password" type="password" :class="{'form-control': true, 'is-invalid' : errors.has('password_confirmation')}"  name="password_confirmation" value="" required >           
                                </div>
                                <span v-show="errors.has('password_confirmation')" class="invalid-feedback d-block" role="alert">
                                  <strong>{{ errors.first('password_confirmation') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <select name="country_code" v-validate="'required'" v-model="country_code" :class="{'custom-select form-control': true, 'is-invalid' : errors.has('country_code')}" @change="time_zone_id = null" required>
                                        <option v-for="(name, value) in lists.countries" :value="value" :key="value" >{{ name }}</option>
                                    </select>         
                                </div>
                                <span v-show="errors.has('country_code')" class="invalid-feedback d-block" role="alert">
                                  <strong>{{ errors.first('country_code') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    </div>
                                    <select name="time_zone_id" v-validate="'required'" v-model="time_zone_id" :class="{'custom-select form-control': true, 'is-invalid' : errors.has('time_zone_id')}" required>
                                        <option v-for="(name, value) in lists.timeZones[country_code]" :value="value" :key="value" >{{ name }}</option>
                                    </select>         
                                </div>
                                <span v-show="errors.has('time_zone_id')" class="invalid-feedback d-block" role="alert">
                                  <strong>{{ errors.first('time_zone_id') }}</strong>
                                </span>
                            </div>
                            <div v-if="loading" class="text-center">
                                <button  class="d-block btn btn-outline-primary border-0 mt-4" type="button" disabled>
                                  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                  Loading...
                                </button>
                            </div>                             
                            <div class="text-center">
                                <button type="submit" :disabled="!isFormValid || loading" :class="{'btn btn-primary my-4': true, 'disabled': !isFormValid || loading }" >Create Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  
</template>

<script>
import {formData} from '../../helpers/general'
import headerGuest from '../layouts/headers/guest'
export default {
    name: 'login',
    components: { headerGuest },
    data(){
      return {        
        loading: false,
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        country_code: 'VE',
        time_zone_id: 418,
        lists: { countries: [], timeZones: []},
      }
    },
    created(){
        this.$validator.extend('unique', {
            getMessage: field => `The ${field} has already been taken.`,
            validate: value => (
            new Promise(resolve => {
                this.$store.dispatch("sendGet", { url: `/api/auth/unique/${value}`, auth: false })
                .then(res => {
                    resolve({
                        valid: res.data
                    })
                })
            })
            )
        })
    },
    mounted(){
      this.$store.dispatch('sendGet', { url:`/api/auth/signup`}).then(res => {
        if(res.data.countries) this.lists.countries = res.data.countries
        if(res.data.timeZones) this.lists.timeZones = res.data.timeZones    
      }).catch(e => {})
    },
    methods: {
      authenticate(){
        this.loading = true;
        this.$store.dispatch('sendPost', { url:'/api/auth/signup', data: formData(this.$refs.form)})
          .then((res) => {
                this.loading = false;
		        this.$toasted.success(res.message)
                this.$router.push({path: '/login'});
          })
          .catch((error) => {
                this.loading = false;
		        this.$toasted.error('An error has occurred, please check them and try again.')
          });
      },
    },
    computed: {
        isFormValid() {
        let valid =  Object.keys(this.fields).filter(key => this.fields[key].valid);
          return valid.length === Object.keys(this.fields).length ;
        }
    }
}
</script>