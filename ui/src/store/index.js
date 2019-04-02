import Vue from 'vue'
import Vuex from 'vuex'
import { serverURL } from '../config'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    tutorials: {
      my: [],
      observing: [],
      moderating: []
    },
    observedTrainings: [],
    trainingParticipating: [],
    users: [],
    userID: null,
    serverURL: serverURL
  },
  mutations: {
    setUser (state, user) {
      state.userID = user.ID
    },
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
      if (props.moderators) {
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
      }
      if (props.observers) {
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
    },
    setObservedTrainings (state, trainings) {
      state.observedTrainings = []
      console.log(trainings)
      trainings.forEach(t => {
        console.log(t)
        state.observedTrainings.push(t)
      })
      console.log(state.observedTrainings)
    },
    deleteObservedTraining (state, id) {
      state.observedTrainings = state.observedTrainings.filter(t => t.id !== id)
    },
    updateObservedTraining (state, info) {
      for (let i = 0; i < state.observedTrainings.length; i++) {
        if (state.observedTrainings[i].id === info.id) {
          for (let key in info) {
            state.observedTrainings[i][key] = info[key]
          }
        }
      }
    },
    addObservedTraining (state, training) {
      state.observedTrainings.unshift(training)
    },
    setParticipatingTrainings (state, trainings) {
      state.trainingParticipating = []
      trainings.forEach(t => {
        state.trainingParticipating.push(t)
      })
    },
    deleteParticipatingTraining (state, id) {
      state.trainingParticipating = state.trainingParticipating.filter(p => p.id !== id)
    },
    updateParticipantInfo (state, info) {
      console.log(info)
      for (let i = 0; i < state.trainingParticipating.length; i++) {
        if (state.trainingParticipating[i].id === info.id) {
          console.log('We found one')
          for (let key in info.props) {
            if (info.props.hasOwnProperty(key)) {
              state.trainingParticipating[i][key] = info.props[key]
            }
          }
        }
      }
    },
    updateParticipatingTraining (state, info) {
      console.log(info)
      for (let i = 0; i < state.trainingParticipating.length; i++) {
        if (state.trainingParticipating[i].training &&
          state.trainingParticipating[i].training.id === info.id) {
          console.log('We found one')
          for (let key in info.props) {
            if (info.props.hasOwnProperty(key)) {
              state.trainingParticipating[i].training[key] = info.props[key]
            }
          }
          console.log(state.trainingParticipating[i])
        }
      }
      if (info.callback) {
        info.callback()
      }
    },
    addParticipatingTraining (state, options) {
      state.trainingParticipating.unshift(options)
    },
    updateParticipantsInfo (state, options) {
      for (let i = 0; i < state.observedTrainings.length; i++) {
        let participants = state.observedTrainings[i]['participants']
        for (let participant in participants) {
          if (participant.id === options.id) {
            for (let k in options.props) {
              participant[k] = options.props[k]
            }
          }
        }
        state.observedTrainings[i] = participants
      }
      if (options.callback) {
        options.callback()
      }
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
