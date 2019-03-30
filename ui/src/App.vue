<template>
<div id="app" class="container-fluid">
  <div class="toolbar">
    <nav style="margin-bottom: 25px">
      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
        <router-link :to="{name: 'Participating'}"
                     class="nav-link nav-item"
                     tag="li"
        >
          Participating Trainings
        </router-link>
        <router-link :to="{name: 'Observing'}"
                     class="nav-link nav-item"
                     tag="li"
        >
          Observing Trainings
        </router-link>
      </div>
    </nav>
  </div>
  <div class="landing-page-component">
    <div class="trainings-list-component">
      <div class="new-training-button-holder">
        <b-btn @click="showCreateWindow = true"
               variant="outline-primary"
               class="icon-button">
          <v-icon name="plus" />
        </b-btn>
      </div>
      <router-view/>
    </div>
    <!--<div>
        <trainings-list-component></trainings-list-component>
    </div>-->
    <div class="tutorials-button-holder">
      <b-btn variant="outline-primary"
             class="tutorials-button"
             @click="showTutorial">
        Tutorials
      </b-btn>
    </div>
    <transition>
      <div class="modal-window-canvas" v-if="showTutorials" @click="hideTutorials(false)">
        <div class="modal-window-holder" @click="$event.stopPropagation()">
          <tutorials-component @close="hideTutorials($event)"></tutorials-component>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="modal-window-canvas" @click="hideCreate()" v-if="showCreateWindow">
        <div class="modal-window-holder" @click="$event.stopPropagation()">
          <create-training-component @close="hideCreate"></create-training-component>
        </div>
      </div>
    </transition>
  </div>
</div>
</template>

<script>
import TutorialsComponent from './components/Tutorials/TutorialsComponent.vue'
import CreateTrainingComponent from './components/Trainings/CreateTrainingComponent.vue'
export default {
  name: 'App',
  components: {
    TutorialsComponent,
    CreateTrainingComponent
  },
  data () {
    return {
      showTutorials: false,
      showCreateWindow: false
    }
  },
  mounted () {
    this.loadMyUser()
    this.loadTutorials()
    this.loadUsers()
  },
  methods: {
    loadTutorials () {
      this.axios.get('/user/tutorials').then(response => {
        console.log(response.data)
        this.$store.commit('setTutorials', {
          type: 'my',
          tutorials: response.data.my
        })
        this.$store.commit('setTutorials', {
          type: 'observing',
          tutorials: response.data.observing
        })
        this.$store.commit('setTutorials', {
          type: 'moderating',
          tutorials: response.data.moderating
        })
        // this.$store.commit('setTutorials', response.data)
      }).catch(err => {
        alert('Error occurred')
        console.log(err)
      })
    },
    loadUsers () {
      this.axios.get('/users').then(response => {
        this.$store.commit('setUsers', response.data)
        console.log(response.data)
      })
    },
    loadMyUser () {
      this.axios.get('/user').then(response => {
        this.$store.commit('setUser', response.data)
      })
    },
    showTutorial () {
      this.showTutorials = true
    },
    hideTutorials (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.showTutorials = false
    },
    hideCreate (force = false) {
      if (!(force || confirm('Do you wanna close window'))) {
        return
      }
      this.showCreateWindow = false
    }
  }
}
</script>

<style lang="sass">
#app
  font-family: 'Avenir', Helvetica, Arial, sans-serif
  -webkit-font-smoothing: antialiased
  -moz-osx-font-smoothing: grayscale
  text-align: center
  color: #2c3e50
  margin-top: 60px
  min-height: 90vh

.landing-page-component
  padding-bottom: 2rem

.tutorials-button-holder
  height: 2rem
  text-align: right !important
  margin-top: 1rem
  position: relative

.trainings-list-component
  position: relative

.new-training-button-holder
  height: 2rem
  text-align: right

.trainings-list
  padding-top: 1rem

.active-module
  text-decoration: underline

.navbar
  color: #7c7c7c
  box-shadow: 0 0 3px 3px #30303020 inset

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active
  border-color: rgb(222, 226, 230) !important

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.router-link-exact-active
  background-color: #ededed !important
  border-color: transparent transparent #f3f3f3 !important
  border-bottom: 2px solid !important
  font-size: 20px
  font-weight: bold

.nav-tabs .nav-link
  border: 1px solid transparent
  border-top-left-radius: .25rem
  border-top-right-radius: .25rem
  font-size: 20px
  margin: 0 5px
  cursor: pointer
  color: #7c7c7c
  font-weight: 700


</style>
