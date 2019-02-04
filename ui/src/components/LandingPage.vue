<template>
    <div class="landing-page-component">
        <div>
            <trainings-list-component></trainings-list-component>
        </div>
        <div class="tutorials-button-holder">
            <b-btn variant="outline-primary" class="tutorials-button" @click="showTutorial">
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
<style>
    .landing-page-component{
        padding-bottom: 1rem;
    }
    .tutorials-button-holder{
        height: 2rem;
        position: relative;
    }
    .tutorials-button{
        position: absolute;
        bottom: 0;
        right: 0;
    }
</style>
