<template>
  <div>
    <header-user  v-if="isLoggedIn" name="Change of Password"/>
    <header-guest v-else title="Reset Password"/>
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card shadow border-0" :class="{'bg-secondary':isLoggedIn}">
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" @submit.prevent="submit" ref="form">
                <input name="_method" type="hidden" value="PUT">
                <div v-if="isLoggedIn" class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-unlock-alt"></i>
                      </span>
                    </div>
                    <input
                      v-validate="'required|min:6|exist'"
                      v-model="currentPassword"
                      placeholder="Current Password"
                      type="password"
                      :class="{'form-control': true, 'is-invalid' : errors.has('currentPassword')}"
                      name="currentPassword"
                      value
                      required
                    >
                  </div>
                  <span
                    v-show="errors.has('currentPassword')"
                    class="invalid-feedback d-block"
                    role="alert"
                  >
                    <strong>{{ errors.first('currentPassword') }}</strong>
                  </span>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-unlock-alt"></i>
                      </span>
                    </div>
                    <input
                      v-validate="'required|min:6'"
                      v-model="password"
                      placeholder="New Password"
                      type="password"
                      :class="{'form-control': true, 'is-invalid' : errors.has('password')}"
                      name="password"
                      value
                      required
                      ref="password"
                    >
                  </div>
                  <span
                    v-show="errors.has('password')"
                    class="invalid-feedback d-block"
                    role="alert"
                  >
                    <strong>{{ errors.first('password') }}</strong>
                  </span>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-unlock-alt"></i>
                      </span>
                    </div>
                    <input
                      v-validate="'required|min:6|confirmed:password'"
                      v-model="confirmation"
                      placeholder="Confirm Password"
                      type="password"
                      :class="{'form-control': true, 'is-invalid' : errors.has('confirmation')}"
                      name="confirmation"
                      value
                      required
                    >
                  </div>
                  <span
                    v-show="errors.has('confirmation')"
                    class="invalid-feedback d-block"
                    role="alert"
                  >
                    <strong>{{ errors.first('confirmation') }}</strong>
                  </span>
                </div>
                <div v-if="loading" class="text-center">
                  <button
                    class="d-block btn btn-outline-primary border-0 mt-4"
                    type="button"
                    disabled
                  >
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Loading...
                  </button>
                </div>
                <div class="text-center">
                  <button
                    type="submit"
                    :disabled="!isFormValid || loading"
                    :class="{'btn btn-primary my-4': true, 'disabled': !isFormValid || loading }"
                  >Reset Password</button>
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
import { formData, addJsonToFormData } from "./../../../helpers/general";
import headerUser from "./../../user/header";
import headerGuest from "../../layouts/headers/guest"

export default {
  components: {
    headerUser,
    headerGuest
  },
  data() {
    return {
      loading: false,
      currentPassword: null,
      password: null,
      confirmation: null,
      user: null,
    };
  },
  computed: {
    currentUser() {
      return this.user || this.$store.getters.currentUser;
    },
    token(){
      return this.$route.params.token
    },
    isLoggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    isFormValid() {
      let valid = Object.keys(this.fields).filter(
        key => this.fields[key].valid
      );
      return valid.length === Object.keys(this.fields).length;
    }
  },
  created() {
    if(this.isLoggedIn){
      this.$validator.extend("exist", {
        getMessage: field => `The current password doesn't match.`,
        validate: value =>
          new Promise(resolve => {
            this.$store
              .dispatch("sendPost", {
                url: `/api/password/current`,
                data: addJsonToFormData({ id: this.currentUser.id, password: value },null)
              })
              .then(res => {
                resolve({
                  valid: res.data
                });
              });
          })
      });
    } else{
      this.validateToken()
    }
  },
  methods: {
    submit() {
      this.loading = true;
      this.$store
        .dispatch("sendPost", {
          url: `/api/password/reset`,
          data: addJsonToFormData({ id: this.currentUser.id , token: this.token },formData(this.$refs.form))
        })
        .then(res => {
          this.loading = false;
          this.$toasted.success(res.message);
          if(this.isLoggedIn) this.$router.push('/login')
        })
        .catch(error => {
          this.loading = false;
          this.$toasted.error(
            "An error has occurred, please check them and try again."
          );
        });
    },
    validateToken(){
      this.$store
        .dispatch("sendPost", {
          url: `/api/password/validate`,
          data: addJsonToFormData({ token: this.token },null)
        })
        .then(res => {
          if(res.data.user) this.user = res.data.user          
        })
        .catch(error => {
          this.$toasted.error("the access token provided has expired.");
          this.$router.push('/password/forgot')
        });
    }
  }
};
</script>
