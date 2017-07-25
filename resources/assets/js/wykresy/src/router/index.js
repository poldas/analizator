import Vue from 'vue'
import Router from 'vue-router'
import ChartList from '@/components/views/ChartView'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'ChartsView',
      component: ChartList
    }
  ]
})
