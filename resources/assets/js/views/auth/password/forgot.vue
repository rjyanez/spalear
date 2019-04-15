<template>
  <div v-if="!isLoggedIn">
    <header-guest title="Forgot Password"/>
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" @submit.prevent="submit" ref="form">
                <div class="form-group mb-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </div>
                    <input
                      v-validate="'required|email|exist'"
                      v-model="email"
                      class="form-control"
                      :class="{'is-invalid' : errors.has('email')}"
                      placeholder="Email"
                      type="email"
                      name="email"
                      value
                      required
                      autofocus
                    >
                  </div>
                  <span v-show="errors.has('email')" class="invalid-feedback d-block" role="alert">
                    <strong>{{ errors.first('email') }}</strong>
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
                  >Send Validation</button>
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
import { formData } from "./../../../helpers/general";
import headerGuest from '../../layouts/headers/guest'

export default {
  components: {
    headerGuest
  },
  data() {
    return {
      loading: false,
      email: null
    };
  },
  computed: {
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
    this.$validator.extend("exist", {
      getMessage: field => `The ${field} is not registered.`,
      validate: value =>
        new Promise(resolve => {
          this.$store
            .dispatch("sendGet", {
              url: `/api/auth/unique/${value}`,
              auth: false
            })
            .then(res => {
              resolve({
                valid: !res.data
              });
            });
        })
    });
  },
  methods: {
    submit() {
      this.loading = true;
      this.$store
        .dispatch("sendPost", {
          url: `/api/password/forgot`,
          data: formData(this.$refs.form)
        })
        .then(res => {
          this.loading = false;
          this.$toasted.success(res.message);
        })
        .catch(error => {
          this.loading = false;
          this.$toasted.error(
            "An error has occurred, please check them and try again."
          );
        });
    }
  }
};
</script>
