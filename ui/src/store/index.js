import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    tutorials: {
      my: [],
      observing: [],
      moderating: []
    },
    users: [],
    userID: 1,
    serverURL: 'http://localhost/azercosmos-training-certification/api/public'
  },
  mutations: {
    setTutorials (state, options) {
      state.tutorials[options.type] = options.tutorials
    },
    addTutorial (state, options) {
      state.tutorials[options.type].push(options.tutorial)
    },
    deleteTutorial (state, id) {
      for (let type in state.tutorials) {
        if (state.tutorials.hasOwnProperty(type)) {
          state.tutorials[type] = state.tutorials[type].filter(x => x.id !== id)
        }
      }
    },
    updateTutorial (state, info) {
      let id = info.id
      let props = info.props
      let isModerator = props.moderators && props.moderators.some(m => m.moderator_id === state.userID)
        // Delete from moderating if you're not moderator anymore
      if (!isModerator &&
        state.tutorials['moderating'].some(t => t.id === id)) {
        state.tutorials['moderating'] = state.tutorials['moderating'].filter(x => x.id !== id)
      }
      // Add into moderating if you're moderator
      if (isModerator &&
        !state.tutorials['moderating'].some(t => t.id === id)) {
        state.tutorials['moderating'].push(props)
      }
      // Same two actions for observers
      let isObserver = props.observers && props.observers.some(m => m.observer_id === state.userID)
      // Delete from moderating if you're not moderator anymore
      if (!isObserver &&
        state.tutorials['observing'].some(t => t.id === id)) {
        state.tutorials['observing'] = state.tutorials['observing'].filter(x => x.id !== id)
      }
      // Add into moderating if you're moderator
      if (isObserver &&
        !state.tutorials['observing'].some(t => t.id === id)) {
        state.tutorials['observing'].push(props)
      }
      for (let type in state.tutorials) {
        if (state.tutorials.hasOwnProperty(type)) {
          for (let i = 0; i < state.tutorials[type].length; i++) {
            if (state.tutorials[type][i].id === id) {
              for (let key in props) {
                if (props.hasOwnProperty(key)) {
                  state.tutorials[type][i][key] = props[key]
                }
              }
            }
          }
        }
      }
    },
    setUsers (state, users) {
      state.users = users
    }
  },
  getters: {
    usersOptions: (state) => {
      return state.users.map(u => {
        return {
          text: u.NAME,
          value: u.ID
        }
      })
    }
  }
})
