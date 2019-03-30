<template>
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
.trainings-list-component{
    position: relative;
}
.new-training-button-holder{
    height: 2rem;
    text-align: right;
}
.trainings-list{
    padding-top: 1rem;
}
</style>
