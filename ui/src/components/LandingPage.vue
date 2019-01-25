<template>
    <div>
        <h1>Landing page</h1>
        <div>
            <trainings-list-component></trainings-list-component>
        </div>
        <!--<div>
            <tutorials-component></tutorials-component>
        </div>-->
    </div>
</template>
<script>
import TutorialsComponent from './Tutorials/TutorialsComponent.vue'
import TrainingsListComponent from './Trainings/TrainingsListComponent.vue'
export default{
  components: {
    TutorialsComponent,
    TrainingsListComponent
  }
}
</script>
<style>

</style>
