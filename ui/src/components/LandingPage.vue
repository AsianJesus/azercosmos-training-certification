<template>
    <div>
        <h1>Landing page</h1>
        <div>
            <trainings-list-component></trainings-list-component>
        </div>
        <div>
            <b-btn variant="outline-primary" @click="showTutorial">
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
    </div>
</template>
<script>
import TutorialsComponent from './Tutorials/TutorialsComponent.vue'
import TrainingsListComponent from './Trainings/TrainingsListComponent.vue'
export default{
  components: {
    TutorialsComponent,
    TrainingsListComponent
  },
  data () {
    return {
      showTutorials: false
    }
  },
  methods: {
    showTutorial () {
      this.showTutorials = true
    },
    hideTutorials (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.showTutorials = false
    }
  }
}
</script>
<style lang="scss">

</style>
