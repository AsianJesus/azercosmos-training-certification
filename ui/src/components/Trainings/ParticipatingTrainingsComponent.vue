<template>
    <div class="participating-trainings-component">
        <table class="table">
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Type
                </th>
                <th>
                    Start
                </th>
                <th>
                    End
                </th>
                <th>
                    Status
                </th>
                <th></th>
            </tr>
            <tr v-for="(p, id) in trainings" v-bind:key="id">
                <td>
                    {{ p.training.title }}
                </td>
                <td>
                    {{ p.training.is_test_exam ? 'Test' : 'Non test'}}
                </td>
                <td>
                    {{ p.start_date }}
                </td>
                <td>
                    {{ p.end_date }}
                </td>
                <td>
                    {{ getStatusName(p.status) }}
                </td>
                <td>
                    <b-btn variant="outline-success" @click="viewTraining(p.id)">
                        View
                    </b-btn>
                </td>
            </tr>
        </table>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="selectedTraining !== null" @click="selectedTraining = null">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <view-training-component :id="selectedTraining"></view-training-component>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import { getStatus } from '../../js/statuses'
import ViewTrainingAsParticipantComponent from './ViewTrainingAsParticipantComponent.vue'
export default{
  components: {
    ViewTrainingComponent: ViewTrainingAsParticipantComponent
  },
  data () {
    return {
      filters: [],
      currentPage: null,
      totalPages: null,
      orderBy: null,
      orderAsc: false,
      selectedTraining: null
    }
  },
  computed: {
    trainings () {
      return this.$store.state.trainingParticipating
    }
  },
  mounted () {
    this.loadTrainings()
  },
  methods: {
    loadTrainings (page = 1) {
      this.axios.get('/users/' + this.$store.state.userID + '/participating-trainings', {
        params: {
          page: page,
          filters: this.filters,
          orderBy: this.orderBy,
          order: this.orderAsc ? 'asc' : 'desc'
        }
      }).then(response => {
        console.log(response.data)
        this.currentPage = response.data.current_page
        this.totalPages = response.data.last_page
        this.$store.commit('setParticipatingTrainings', response.data.data)
      })
    },
    viewTraining (id) {
      this.selectedTraining = id
    },
    getStatusName: getStatus
  }
}
</script>
<style>

</style>
