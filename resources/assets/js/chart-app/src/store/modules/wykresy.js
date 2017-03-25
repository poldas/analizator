import axios from 'axios'
const state = {
  wykresy: []
};

const mutations = {
  'SET_WYKRESY' (state, wykresy) {
    state.wykresy = wykresy;
  },
  'SAVED_WYKRES' (state, item) {
    state.wykresy.find(i => i.id === item.id);
  },
  'SET_TO_PRINT' (state, item) {
    let finded = state.wykresy.find(i => i.id === item.id);
    if(!finded.options) {
      finded.options = {}
      finded.options.show = true
    } else {
      finded.options.show = !finded.options.show
    }
    console.log(finded)
  }
};

const actions = {
  fetchData: ({commit}, id_analiza) => {
    return new Promise((resolve, reject) => {
      axios.get(`http://localhost:8000/wykresy/${id_analiza}`)
        .then(response => {
          if (response.status === 200) {
            commit('SET_WYKRESY', response.data);
            resolve(response.data, id_analiza)
          }
        }).catch((e) => {reject(e)});
    })
  },
  save: ({commit}, item) => {
    return new Promise((resolve, reject) => {
      axios.post(`http://localhost:8000/wykresy/${item.id}`, item)
        .then(data => {
          if (data) {
            commit('SAVED_WYKRES', item);
            resolve(data)
          }
        }).catch((e) => {reject(e)});

    })
  },
  print: ({commit, dispatch}, item) => {
    return new Promise((resolve, reject) => {
      commit('SET_TO_PRINT', item);
      dispatch('save', item)
      resolve(item)
    })
  }
};

const getters = {
  wykresy: state => {
    return state.wykresy;
  }
};

export default {
  state,
  mutations,
  actions,
  getters
};