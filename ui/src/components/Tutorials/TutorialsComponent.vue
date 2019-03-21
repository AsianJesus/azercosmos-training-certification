<template>
    <div class="tutorials-component">
        <div    style="text-align: left;">
            <b-btn variant="outline-success" @click="showNewTutorial = true">
                Create new tutorial
                <v-icon name="plus" />
            </b-btn>
        </div>
        <div class="tutorials-list">
            <tutorial-list-component></tutorial-list-component>
        </div>
        <transition name="fade">
            <div class="new-tutorial modal-window-canvas" @click="closeWindow()" v-if="showNewTutorial">
                <div class="new-tutorial-holder modal-window-holder" @click="$event.stopPropagation()">
                    <new-tutorial-component @close="closeWindow($event)"></new-tutorial-component>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import TutorialListComponent from './TutorialListComponent.vue'
import NewTutorialComponent from './NewTutorialComponent.vue'
export default{
  components: {
    TutorialListComponent,
    NewTutorialComponent
  },
  data () {
    return {
      showNewTutorial: false
    }
  },
  methods: {
    closeWindow (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.showNewTutorial = false
    }
  }
}
</script>
<style>

</style>
