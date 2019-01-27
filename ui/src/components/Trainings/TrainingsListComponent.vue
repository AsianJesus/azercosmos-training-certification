<template>
    <div class="trainings-list-component">
        <b-btn @click="showCreateWindow = true" variant="outline-primary">
            Record new training
        </b-btn>
        <b-tabs>
            <b-tab title="Participating tutorials">
                <participating-trainings-component></participating-trainings-component>
            </b-tab>
            <b-tab title="Observing tutorials">
                <observing-trainings-component></observing-trainings-component>
            </b-tab>
        </b-tabs>
        <transition name="fade">
            <div class="modal-window-canvas" @click="hideCreate()" v-if="showCreateWindow">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <create-training-component @close="hideCreate"></create-training-component>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import ParticipatingTrainingsComponent from './ParticipatingTrainingsComponent.vue'
import ObservingTrainingsComponent from './ObservingTrainingsComponent.vue'
import CreateTrainingComponent from './CreateTrainingComponent.vue'
export default{
  components: {
    ParticipatingTrainingsComponent,
    ObservingTrainingsComponent,
    CreateTrainingComponent
  },
  data () {
    return {
      showCreateWindow: false
    }
  },
  methods: {
    hideCreate (force = false) {
      if (!(force || confirm('Do you wanna close window'))) {
        return
      }
      this.showCreateWindow = false
    }
  }
}
</script>
<style>

</style>
