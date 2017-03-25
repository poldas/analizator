import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import ChartsView from '../views/ChartsView.vue'
export default new Router({
  // mode: 'history',
  routes: [
    { path: '/:id_analiza', name: 'charts', component: ChartsView }
  ]
})