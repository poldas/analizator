import Vue from 'vue'
import Vuetify from 'vuetify'
Vue.use(Vuetify)

import VueHighcharts from 'vue-highcharts';
Vue.use(VueHighcharts);

import store from './store/index'
import router from './router/index'
import App from './App.vue'
import { sync } from 'vuex-router-sync'

sync(store, router)

// require('vue-animate/dist/vue-animate.min.css')

const app = new Vue(Vue.util.extend({
  router,
  store
}, App))

export { app, router, store }
