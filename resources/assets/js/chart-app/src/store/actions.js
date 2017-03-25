import axios from 'axios'

const state = {
  wykresy: []
};


const mutations = {
  'SET_WYKRESY' (state, wykresy) {
    state.wykresy = wykresy;
  },
  'RND_WYKRESY' (state) {
    state.wykresy.forEach(stock => {
      stock.price = Math.round(stock.price * (1 + Math.random() - 0.5));
    });
  }
};


const actions = {
  loadData: ({commit}) => {
  return new Promise((resolve, reject) => {
    axios.get('http://localhost:8000/analiza/parsuj/1')
      .then(data => {
        if (data) {
          commit('SET_WYKRESY', data);
          resolve(data, item)
        }
      }).catch((e) => {
      reject(e)
    });
  })
}
}

export default {
  actions,
  state,
  mutations
}