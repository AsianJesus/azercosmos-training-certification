<template>
    <div class="participating-trainings-component">
        <filter-component v-model="filters" @input="delayedLoad()"></filter-component>
        <div class="participating-trainigs-loaded" v-if="!isLoading">
            <table class="table training-table">
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Trainer
                    </th>
                    <th>
                        Test Exam
                    </th>
                    <th @click="changeOrder('start_date')" class="order-button">
                        Record date
                        <span v-if="orderBy === 'start_date'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('updated_at')" class="order-button">
                        Latest change
                        <span v-if="orderBy === 'update_at'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th><!--
                    <th @click="changeOrder('status')" class="order-button">
                        Status
                        <span v-if="orderBy === 'status'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>-->
                </tr>
                <tr v-for="(p, i) in trainings" v-bind:key="'participant' + i"
                    @click="viewTraining(p.id)" style="cursor: pointer;" :class="'training-status-' + p.status">
                    <td>
                        {{ p.training.title }}
                    </td>
                    <td>
                        {{ p.training.originator ? p.training.originator.NAME : '' }}
                    </td>
                    <td>
                        {{ p.training.is_test_exam ? 'Yes' : 'No'}}
                    </td>
                    <td>
                        {{ p.start_date }}
                    </td>
                    <td>
                        {{ p.updated_at ? p.updated_at.substring(0, 10) : p.created_at.substring(0, 10) }}
                    </td>
                </tr>
            </table>
            <b-pagination v-bind:value="currentPage" :total-rows="totalPages" :per-page="1" @input="changePage"
                          v-if="currentPage && totalPages > 1">

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
              return value ? '%' + value + '%' : null
            }
          },
          value: null
        },
        {
          text: 'Status',
          meta: {
            name: 'status',
            type: 'p'
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
      let params = {
        page: page
      }
      if (this.orderBy) {
        params['orderBy'] = this.orderBy
        params['order'] = this.orderAsc ? 'asc' : 'desc'
      }
      let trainingFilters = this.filters.filter(f => (!f.meta.type || f.meta.type === 't') && f.value).map(f => {
        return {
          name: f.meta.name,
          value: f.meta.modifier ? f.meta.modifier(f.value) : f.value
        }
      })
      if (trainingFilters.length) {
        params['t_filters'] = trainingFilters
      }
      let participantFilters = this.filters.filter(f => f.meta.type === 'p' && f.value).map(f => {
        return {
          name: f.meta.name,
          value: f.meta.modifier ? f.meta.modifier(f.value) : f.value
        }
      })
      if (participantFilters.length) {
        params['p_filters'] = participantFilters
      }
      this.axios.get('/users/' + this.$store.state.userID + '/participating-trainings', {
        params: params
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
    changeOrder (order) {
      if (order === this.orderBy) {
        this.orderAsc ^= true
      } else {
        this.orderBy = order
        this.orderAsc = true
      }
      this.delayedLoad()
    },
    delayedLoad: lodash.debounce(function () {
      this.loadTrainings()
    }, 1000)
  }
}
</script>
<style>

</style>
