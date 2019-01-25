<template>
    <div class="trainings-list-component">
        <b-tabs>
            <b-tab title="Participating tutorials">
                <participating-trainings-component></participating-trainings-component>
            </b-tab>
            <b-tab title="My tutorials">
                <my-trainings-component></my-trainings-component>
            </b-tab>
            <b-tab title="Observing tutorials">
                <observing-trainings-component></observing-trainings-component>
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
import ParticipatingTrainingsComponent from './ParticipatingTrainingsComponent.vue'
import MyTrainingsComponent from './MyTrainingsComponent.vue'
import ObservingTrainingsComponent from './ObservingTrainingsComponent.vue'
export default{
  components: {
    ParticipatingTrainingsComponent,
    MyTrainingsComponent,
    ObservingTrainingsComponent
  },
  data () {
    return {

    }
  },
  mounted () {

  },
  methods: {

  }
}
</script>
<style>

</style>
