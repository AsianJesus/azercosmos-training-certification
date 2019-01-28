<template>
    <div class="participating-trainings-component">
        <filter-component v-model="filters" @input="delayedLoad()"></filter-component>
        <div class="participating-trainigs-loaded" v-if="!isLoading">
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
            <b-pagination v-bind:value="currentPage" :total-rows="totalPages" :per-page="1" @input="changePage">

            </b-pagination>
        </div>
        <div class="participating-trainings-not-loaded" v-else>
            <h4>
                Sorry, but your trainings are not loaded yet
            </h4>
        </div>
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
import { getStatus, statusOptions } from '../../js/statuses'
import ViewTrainingAsParticipantComponent from './ViewTrainingAsParticipantComponent.vue'
import FilterComponent from '../Utilities/FilterComponent.vue'
import lodash from 'lodash'
export default{
  components: {
    ViewTrainingComponent: ViewTrainingAsParticipantComponent,
    FilterComponent
  },
  data () {
    return {
      currentPage: null,
      totalPages: null,
      orderBy: null,
      orderAsc: false,
      selectedTraining: null,
      isLoading: false,
      filters: [
        {
          text: 'Name',
          meta: {
            name: 'title',
            modifier (value) {
              return '%' + value + '%'
            },
          },
          value: null
        },
        {
          text: 'Status',
          meta: {
            name: 'status',
            type: 'p',
          },
          value: null,
          options: statusOptions
        }
      ]
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
      if (this.isLoading) {
        return
      }
      this.isLoading = true
      let trainingFilters = this.filters.filter(f => !f.meta.type || f.meta.type === 't' && f.value).map(f => {
        return {
          name: f.meta.name,
          value: f.meta.modifier ? f.meta.modifier(f.value) : f.value
        }
      })
      let participantFilters = this.filters.filter(f => f.meta.type === 'p' && f.value).map(f => {
        return {
          name: f.meta.name,
          value: f.meta.modifier ? f.meta.modifier(f.value) : f.value
        }
      })
      this.axios.get('/users/' + this.$store.state.userID + '/participating-trainings', {
        params: {
          page: page,
          p_filters: participantFilters,
          t_filters: trainingFilters,
          orderBy: this.orderBy,
          order: this.orderAsc ? 'asc' : 'desc'
        }
      }).then(response => {
        this.isLoading = false
        console.log(response.data)
        this.currentPage = response.data.current_page
        this.totalPages = response.data.last_page
        this.$store.commit('setParticipatingTrainings', response.data.data)
      }).catch(() => {
        this.isLoading = false
      })
    },
    viewTraining (id) {
      this.selectedTraining = id
    },
    getStatusName: getStatus,
    changePage (page) {
      if (page !== this.currentPage) {
        this.loadTrainings(page)
      }
    },
    delayedLoad: lodash.debounce(function () {
      this.loadTrainings()
    }, 1000)
  }
}
</script>
<style>

</style>
