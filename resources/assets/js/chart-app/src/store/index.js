import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
import wykresy from './modules/wykresy'


export default new Vuex.Store({
  modules: {
    wykresy 
  }
})