
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VeeValidate from 'vee-validate';
import Toasted from 'vue-toasted';


import {routes} from './routes';
import {initialize} from './helpers/general';
import StoreData from './store';
import mainApp from './views/layouts/mainApp.vue';


Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VeeValidate);
Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 3000,
    iconPack: 'fontawesome',
})

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',  
    router,
    store,
    computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        }
    },
    components: {
       'main-app': mainApp
    }
});
