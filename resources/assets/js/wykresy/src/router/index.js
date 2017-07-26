import Vue from 'vue'
import Router from 'vue-router'
const ChartPage = () => import('~pages/ChartPage.vue')

Vue.use(Router)
const routes = [
    {
        path: '/',
        name: 'ChartPage',
        component: ChartPage
    }
]

const router = new Router({
    routes,
    mode: 'history'
})

router.beforeEach((to, from, next) => {
  return next()
})
export default router