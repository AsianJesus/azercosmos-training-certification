<template>
    <div class="tutorials-component">
        <div class="tutorials-component-header">
            <b-btn variant="outline-success"
                   class="tutorial-component-create-new-button"
                   @click="showNewTutorial = true">
                <v-icon name="plus" />
            </b-btn>
            <h4>
                Tutorials
            </h4>
        </div>
        <div class="tutorials-list">
            <tutorial-list-component></tutorial-list-component>
        </div>
        <transition name="fade">
            <div class="new-tutorial modal-window-canvas"
                 @click="closeWindow()"
                 v-if="showNewTutorial">
                <div class="new-tutorial-holder modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="closeWindow(true)"> X </div>
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
<style lang="sass">
@import "../../globals"

.tutorials-component
    position: relative
    padding: 1rem 2rem

.tutorials-component-header
    height: 4rem

.tutorial-component-create-new-button
    @extend %icon-button
    position: absolute
    right: 10px
    top: 1rem

</style>
