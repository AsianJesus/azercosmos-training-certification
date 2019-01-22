<template>
    <div class="tutorials-component">
        <div class="tutorials-list">
            <tutorial-list-component></tutorial-list-component>
        </div>
        <div>
            <b-btn variant="outline-success" @click="showNewTutorial = true">Create new tutorial</b-btn>
        </div>
        <transition name="new-tutorial">
            <div class="new-tutorial modal-window-canvas" @click="closeWindow()" v-if="showNewTutorial"
                 @close="closeWindow(true)">
                <div class="new-tutorial-holder modal-window-holder" @click="$event.stopPropagation()">
                    <new-tutorial-component></new-tutorial-component>
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
@keyframes s {
    0% {
        background-color: transparent;
        opacity: 0;
    }
    60% {
        background-color: transparent;
    }
    100% {
        opacity: 1;
    }
}
.new-tutorial-enter-active{
    animation: s .2s ease;
}
.new-tutorial-leave-active{
    animation: s .2s ease reverse;

}
</style>
