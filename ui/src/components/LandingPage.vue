<template>
    <div class="landing-page-component">
        <div class="trainings-list-component">
            <div class="new-training-button-holder">
                <b-btn @click="showCreateWindow = true"
                       variant="outline-primary"
                       class="icon-button">
                    <v-icon name="plus" />
                </b-btn>
            </div>
            <b-tabs>
                <b-tab title="Participating trainings">
                    <div class="trainings-list">
                        <participating-trainings-component />
                    </div>
                </b-tab>
                <b-tab title="Observing trainings">
                    <div class="trainings-list">
                        <observing-trainings-component />
                    </div>
                </b-tab>
            </b-tabs>
            <transition name="fade">
                <div class="modal-window-canvas" @click="hideCreate()" v-if="showCreateWindow">
                    <div class="modal-window-holder" @click="$event.stopPropagation()">
                        <div class="modal-window-close" @click="hideCreate(true)"> X </div>
                        <create-training-component @close="hideCreate"></create-training-component>
                    </div>
                </div>
            </transition>
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
                    <div class="modal-window-close" @click="hideTutorials(true)"> X </div>
                    <tutorials-component @close="hideTutorials($event)"></tutorials-component>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import TutorialsComponent from './Tutorials/TutorialsComponent.vue'
import TrainingsListComponent from './Trainings/TrainingsListComponent.vue'
import ParticipatingTrainingsComponent from './Trainings/ParticipatingTrainingsComponent.vue'
import ObservingTrainingsComponent from './Trainings/ObservingTrainingsComponent.vue'
import CreateTrainingComponent from './Trainings/CreateTrainingComponent.vue'
export default{
  components: {
    TutorialsComponent,
    TrainingsListComponent,
  ParticipatingTrainingsComponent,
  ObservingTrainingsComponent,
  CreateTrainingComponent
  },
  data () {
    return {
      showTutorials: false,
      showCreateWindow: false
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
<style lang="sass" scoped>
    .landing-page-component
        padding-bottom: 2rem

    .tutorials-button-holder
        height: 2rem
        text-align: right
        margin-top: 1rem
        position: relative

    .trainings-list-component
        position: relative

    .new-training-button-holder
        height: 2rem
        text-align: right

    .trainings-list
        padding-top: 1rem

</style>
