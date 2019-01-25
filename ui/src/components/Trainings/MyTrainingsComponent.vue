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
                <th></th>
            </tr>
            <tr v-for="(p, id) in trainings" v-bind:key="id">
                <td>
                    {{ p.title }}
                </td>
                <td>
                    {{ p.is_test_exam ? 'Test' : 'Non test' }}
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
import ViewTrainingAsOwner from './ViewTrainingAsOwner.vue'
export default{
  components: {
    ViewTrainingComponent: ViewTrainingAsOwner
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
      return this.$store.state.trainings.my
    }
  },
  mounted () {
    this.loadTrainings()
  },
  methods: {
    loadTrainings (page = 1) {
      this.axios.get('/users/' + this.$store.state.userID + '/my-trainings', {
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
        this.$store.commit('setTrainings', {
          type: 'my',
          trainings: response.data.data
        })
      })
    },
    viewTraining (id) {
      this.selectedTraining = id
    }
  }
}
</script>
<style>

</style>
