import Vue from 'vue'
import Router from 'vue-router'
import LandingPage from '@/components/LandingPage'
import ParticipatingTrainings from '../components/Trainings/ParticipatingTrainingsComponent'
import ObservingTrainings from '../components/Trainings/ObservingTrainingsComponent'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Participating',
      component: ParticipatingTrainings
    },
    {
      path: '/observing',
      name: 'Observing',
      component: ObservingTrainings
    }
  ]
})
