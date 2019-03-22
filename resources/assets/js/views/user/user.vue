<template>
  <div>
    <header-user :name="name" />
    <form enctype="multipart/form-data" @submit.prevent="submit" autocomplete="off" ref="form">
      <input name="_method" type="hidden" :value="getMethod" />
      <div class="container-fluid mt--7">
        <div class="row">
          <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
              <div class="row justify-content-center">
                <div class="col-lg-3 order-lg-2">
                  <div class="card-profile-image">
                    <a href="#">
                      <img :src="avatar.startsWith('data') ? avatar : `/uploads/avatar/${avatar}`" class="rounded-circle" ref="img" style="width: 10rem; height: 10rem" />
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                <div class="d-flex justify-content-between">
                  <label class="btn btn-sm btn-success float-right" :class="{ disabled: isEdit }">
                    {{ lists.roles[rol_code] }}
                  </label>
                  <label v-show="isEdit" class="btn btn-sm btn-default float-left" for="avatar">
                    Change
                  </label>
                </div>
              </div>
              <div class="card-body pt-0 pt-md-4 h-100">
                <input id="avatar" type="file" class="d-none" name="avatar" v-validate="'image|size:1000'" ref="file" @change="updateImg($event)" />
                <span v-show="errors.has('avatar')" class="invalid-feedback d-block" role="alert">
                  <strong>{{ errors.first("avatar") }}</strong>
                </span>
                <div class="text-center" :class="{ 'mt-5': !isEdit, 'mt-4': isEdit }">
                  <h2>{{ name }}</h2>
                  {{ email }}
                  <div class="h5 font-weight-300">
                    <i class="ni location_pin mr-2"> 
                      {{ time_zone_id | lableCode(lists.timeZones, true)}} - {{ country_code | lableCode(lists.countries) }}
                    </i>              
                  </div>
                  <div>
                    <i class="ni education_hat mr-2">
                      {{ description }}
                    </i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
              <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                  <h3 class="mb-0">Profile</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h6 class="heading-small text-muted mb-4">User information</h6>
                  </div>
                  <userActions 
                    v-if="$router.currentRoute.name !== 'user.new'"
                    :action="action"
                    :current="id === currentUser.id"
                    :admin="isRole('AD')"
                    @setAction="setAction($event)" 
                  />
                </div>
                <div class="pl-lg-4">
                  <div class="form-group">
                    <label class="form-control-label" for="name">Fullname</label>
                    <input
                      type="text"
                      name="name"
                      id="name"
                      v-validate="'required|alpha_spaces'"
                      v-model="name"
                      placeholder="Fullname"
                      :class="{ 'form-control ': true, 'is-invalid': errors.has('name') }"
                      :readonly="isEdit ? false : true"
                      required
                      :autofocus="isEdit ? true : false"
                    />
                    <span v-show="errors.has('name')" class="invalid-feedback d-block" role="alert">
                      <strong>{{ errors.first("name") }}</strong>
                    </span>
                  </div>
                  <div class="form-group">
                    <label class="form-control-label" for="email">E-Mail Address</label>
                    <input
                      type="email"
                      name="email"
                      id="email"
                      v-validate="'required|email'"
                      v-model="email"
                      placeholder="E-Mail Address"
                      :class="{ 'form-control ': true, 'is-invalid': errors.has('email') }"
                      :readonly="isEdit ? false : true"
                      required
                    />
                    <span v-show="errors.has('email')" class="invalid-feedback d-block" role="alert">
                      <strong>{{ errors.first("email") }}</strong>
                    </span>
                  </div>
                  <div class="form-group">
                    <label class="form-control-label" for="country_code">Country</label>
                    <select
                      v-if="isEdit"
                      name="country_code"
                      id="country_code"
                      v-validate="'required'"
                      v-model="country_code"
                      :class="{ 'custom-select form-control': true, 'is-invalid': errors.has('country_code') }"
                      @change="time_zone_id = null"
                    >
                      <option v-for="(name, value) in lists.countries" :value="value" :key="value">{{ name }}</option>
                    </select>
                    <input v-if="!isEdit" type="text" readonly class="form-control" :value="country_code | lableCode(lists.countries) " />
                    <span v-show="errors.has('country_code')" class="invalid-feedback d-block" role="alert">
                      <strong>{{ errors.first("country_code") }}</strong>
                    </span>
                  </div>
                  <template>
                    <div class="form-group" v-if="country_code">
                      <label class="form-control-label" for="time_zone_id">Time Zone</label>
                      <select
                        v-if="isEdit"
                        name="time_zone_id"
                        id="time_zone_id"
                        v-validate="'required'"
                        v-model="time_zone_id"
                        :class="{ 'custom-select form-control': true, 'is-invalid': errors.has('time_zone_id') }"
                      >
                        <option v-for="(name, value) in lists.timeZones[country_code]" :value="value" :key="value">{{ name }}</option>
                      </select>
                      <input v-if="!isEdit" type="text" readonly class="form-control" :value="time_zone_id | lableCode(lists.timeZones, true)" />
                      <span v-show="errors.has('time_zone_id')" class="invalid-feedback d-block" role="alert">
                        <strong>{{ errors.first("time_zone_id") }}</strong>
                      </span>
                    </div>
                  </template>
                  <div class="form-group">
                    <label class="form-control-label" for="description">Description</label>
                    <textarea
                      rows="3"
                      name="description"
                      id="description"
                      v-model="description"
                      placeholder="About you..."
                      :class="{ 'form-control ': true, 'is-invalid': errors.has('description') }"
                      :readonly="isEdit ? false : true"
                    ></textarea>
                    <span v-show="errors.has('description')" class="invalid-feedback d-block" role="alert">
                      <strong>{{ errors.first("description") }}</strong>
                    </span>
                  </div>
                  <div class="form-group" v-if="isRole('AD') && (id !== currentUser.id || action !== 'update')">
                    <label class="form-control-label" for="rol_code">Role</label>
                    <input v-if="!isEdit" type="text" readonly class="form-control" :value="lists.roles[rol_code]" />
                    <template v-if="(id !== currentUser.id || action === 'create') && isEdit">
                      <div class="d-flex  justify-content-between mt-2">
                        <div class="custom-control custom-switch" v-for="(name, value) in lists.roles" :key="value">
                          <input type="radio" name="rol_code" :value="value" v-model="rol_code" class="custom-control-input" :id="`role-${value}`" />
                          <label class="custom-control-label" :for="`role-${value}`">{{ name }}</label>
                        </div>
                      </div>
                      <span v-show="errors.has('rol_code')" class="invalid-feedback d-block" role="alert">
                        <strong>{{ errors.first("rol_code") }}</strong>
                      </span>
                    </template>
                  </div>

                </div>
                <userTeacher 
                  v-if="rol_code === 'TE'" 
                  :isEdit="isEdit" 
                  :time_schedule="time_schedule" 
                  @callback="setTimeSchedule($event)
                "/>
                <userSubmit 
                  :loading="loading"
                  :action="action"
                  :cancel="$router.currentRoute.name !== 'user.new'"
                  :isEdit="isEdit"
                  :submit="isFormValid"
                  @submitFrom="submit"
                  @setAction="setAction($event)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import { formData, addJsonToFormData, removeEmpty } from "../../helpers/general";
import headerUser  from "./header"      ;
import userTeacher from "./user-teacher"
import userActions from "./user-actions"
import userSubmit  from "./user-submit"

export default {
  name: "user",
  components: {
    headerUser,
    userTeacher,
    userActions,
    userSubmit
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
    id() {
      let id = this.$router.currentRoute.params.id;
      return id === undefined ? this.currentUser.id : id;
    },
    isEdit() {
      return this.action === "show" || this.action === "destroy" ? false : true;
    },
    isFormValid() {
      let valid = Object.keys(this.fields).filter(key => this.fields[key].valid);
      return valid.length === Object.keys(this.fields).length;
    },
    getMethod() {
      return this.routes[this.action]["method"];
    },
    getAction() {
      let action = null;
      if (this.routes[this.action]["action"]) {
        action = this.routes[this.action]["action"];
        if (this.routes[this.action]["param"]) action = action.replace("-", this.id);
      }
      return action;
    }
  },
  data() {
    return {
      loading: false,
      old: null,
      name: null,
      email: null,
      country_code: "VE",
      time_zone_id: 418,
      avatar: `no-img.png`,
      description: null,
      rol_code: "AD",
      time_schedule: {},
      lists: { countries: [], timeZones: [], roles: [] },
      routes: {
        create: {
          method: "POST",
          action: `/api/user/`
        },
        show: {
          method: "GET",
          action: `/api/user/-`
        },
        update: {
          method: "PUT",
          action: `/api/user/-/update`,
          param: true
        },
        destroy: {
          method: "DELETE",
          action: `/api/user/-/destroy`,
          param: true
        }
      },
      action: "show"
    };
  }, 
  filters: {
    lableCode(val, list, child = null){
      if(child){
        for (const parent in list) {
          if (list[parent].hasOwnProperty(val)) {
            return list[parent][val]
          }
        }
      } else {
        return list[val]
      }
    }
  },
  mounted() {
    if (this.$router.currentRoute.name === "user.new") {
      this.setAction("create");
      this.$store.dispatch("sendGet", { url: `/api/user/create`, auth: true }).then(res => {
        if (res.data.roles) this.lists.roles = res.data.roles;
        if (res.data.countries) this.lists.countries = res.data.countries;
        if (res.data.timeZones) this.lists.timeZones = res.data.timeZones;
      });
    } else {
      if (parseInt(this.id) === this.currentUser.id) this.$router.push(`/porfile`);
      this.$store.dispatch("sendGet", { url: `/api/user/${this.id}`, auth: true }).then(res => {
        if (res.data.roles) this.lists.roles = res.data.roles;
        if (res.data.countries) this.lists.countries = res.data.countries;
        if (res.data.timeZones) this.lists.timeZones = res.data.timeZones;
        if (res.data.user) {
          this.setInfo(res.data.user);
          this.old = res.data.user;
        }
      });
    }
  },
  methods: {
    isRole(role) {
      return this.$store.getters.isRole(role);
    },
    updateImg(e) {
      let files = e.target.files;
      if (FileReader && files && files.length) {
        let fr = new FileReader();
        fr.onload = function(e) {
          this.avatar = e.target.result;
        }.bind(this);
        fr.readAsDataURL(files[0]);
      }
    },
    setTimeSchedule(event){
      if(!this.time_schedule.hasOwnProperty(event.day)) this.time_schedule[event.day] = [];
      let day = this.time_schedule[event.day]
      if(day.includes(event.hour)){ 
        day.splice(day.indexOf(event.hour),1)
      } else {
        day.push(event.hour)
      }
      if(day.length === 0) {
        delete this.time_schedule[event.day]
        this.time_schedule = removeEmpty(this.time_schedule)
      } else {
        this.time_schedule[event.day] = day
      }
    },
    setInfo(obj) {
      for (const val in obj) {
        if (this.hasOwnProperty(val)){
          this[val] = obj[val]
        } 
      }
    },
    setInfoEmpty() {
      this.name = null;
      this.email = null;
      this.country_code = "VE";
      this.time_zone_id = 418;
      this.avatar = `no-img.png`;
      this.description = null;
      this.rol_code = "AD";
      this.time_schedule = {};

    },
    setAction(val) {
      if (val === "show" && this.isEdit) this.setInfo(this.old);
      if (val === "create") this.setInfoEmpty();
      if (this.isEdit && this.email !== null) this.$validator.validateAll();
      this.action = val;
    },
    submit() {
      this.loading = true;
      this.$store.dispatch("sendPost", { 
        url: this.getAction, 
        data: addJsonToFormData({ time_schedule: JSON.stringify(this.time_schedule) },formData(this.$refs.form)), 
        auth: true 
      }).then(res => {
        if (res) {
          if (parseInt(this.id) === this.currentUser.id) this.$store.commit("refresh");
          if (this.action === "destroy") {
            this.$router.push({ path: `/porfile` });
          } else if (this.action === "create") {
            this.$router.push({ path: `/user/${res.data.user.id}` });
            this.old = res.data.user;
            this.setInfo(res.data.user);
          } else if (this.action === "update") {
            this.old = res.data.user;
            this.setInfoEmpty();
            this.setInfo(res.data.user);
          }
          this.setAction("show");
          this.$toasted.success(res.message);
        } else {
          this.$toasted.error("Something went wrong, please try again.");
        }
        this.loading = false;
      });
    }
  }
};
</script>