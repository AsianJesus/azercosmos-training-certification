import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    tutorials: []
  },
  mutations: {
    setTutorials (state, tutorials) {
      state.tutorials = tutorials
    },
    addTutorial (state, tutorial) {
      state.tutorials.push(tutorial)
    },
    deleteTutorial (state, id) {
      state.tutorials = state.tutorials.filter(x => x.id !== id)
    }
  },
  getters: {

  }
})
