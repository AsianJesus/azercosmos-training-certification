<template>
    <div class="participating-trainings-component">
        <filter-component v-model="filters" @input="delayedLoad"></filter-component>
        <div class="participating-trainings-loaded" v-if="!isLoading">
            <table class="table training-table">
                <tr>
                    <th @click="changeOrder('id')" class="order-button">
                        ID
                        <span v-if="orderBy === 'id'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('title')" class="order-button">
                        Course Title
                        <span v-if="orderBy === 'title'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('originator')" class="order-button">
                        Trainer
                        <span v-if="orderBy === 'originator'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('is_test_exam')" class="order-button">
                        Test Exam
                        <span v-if="orderBy === 'is_test_exam'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('created_at')" class="order-button">
                        Record date
                        <span v-if="orderBy === 'created_at'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th @click="changeOrder('updated_at')" class="order-button">
                        Latest change
                        <span v-if="orderBy === 'updated_at'">
                            <v-icon name="angle-up" v-if="orderAsc">

                            </v-icon>
                            <v-icon name="angle-down" v-else>

                            </v-icon>
                        </span>
                    </th>
                    <th></th>
                </tr>
                <tr v-for="(p, i) in trainings" v-bind:key="'observing' + i" style="cursor: pointer;"
                    @click="viewTraining(p.id)">
                    <td>
                        {{ p.id }}
                    </td>
                    <td>
                        {{ p.title }}
                    </td>
                    <td>
                        {{ p.originator ? p.originator.NAME : '' }}
                    </td>
                    <td>
                        {{ p.is_test_exam ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        {{ p.created_at.substring(0, 10) }}
                    </td>
                    <td>
                        {{ p.updated_at ? p.updated_at.substring(0,10) : p.create_at.substring(0, 10) }}
                    </td>
                    <td class="control-buttons" @click="$event.stopPropagation()">
                        <b-btn variant="outline-secondary" class="notify-button" @click="notify(p.id)" v-if="canNotify(p.id)">
                            <v-icon name="bell" />
                        </b-btn>
                        <b-btn variant="outline-secondary" class="edit-button" @click="trainingToEdit = p" v-if="canEditTraining(p.id)">
                            <v-icon name="pen" />
                        </b-btn>
                        <b-btn variant="outline-primary" class="add-questions-button" @click="addParticipants(p.id)">
                            <v-icon name="plus" />
                        </b-btn>
                    </td>
                </tr>
            </table>
            <b-pagination v-bind:value="currentPage"
                          :total-rows="totalPages"
                          v-if="currentPage && totalPages > 1"
                          :per-page="1"
                          @input="changePage"
                          align="center">

            </b-pagination>
            <div    style="text-align: right;">
                <download-excel :fields="excelFields"
                                :data="trainings"
                                style="display: inline;">
                    <b-btn variant="outline-success">
                        <v-icon name="file-excel" />
                    </b-btn>
                </download-excel>
            </div>
        </div>
        <div class="participating-trainings-not-loaded" v-else>
            <h4>
                Sorry, but we are still loading
            </h4>
        </div>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="selectedTraining !== null" @click="selectedTraining = null">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <view-training-component :id="selectedTraining" @close="selectedTraining = null" />
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="trainingToAddParticipants !== null" @click="hideAdd()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <add-participants-component :id="trainingToAddParticipants" @close="hideAdd($event)" />
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="trainingToEdit !== null" @click="hideEdit()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <edit-training-component :training="trainingToEdit" @close="hideEdit($event)" />
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import ViewTrainingAsOwner from './ViewTrainingAsObserver.vue'
import AddParticipantsComponent from './AddParticipantsComponent.vue'
import EditTrainingComponent from './EditTrainingComponent.vue'
import FilterComponent from '../Utilities/FilterComponent.vue'
import lodash from 'lodash'
import { observervingFields } from '../../js/excelData'

export default{
  components: {
    ViewTrainingComponent: ViewTrainingAsOwner,
    EditTrainingComponent,
    AddParticipantsComponent,
    FilterComponent
  },
  data () {
    return {
      currentPage: null,
      isLoading: false,
      totalPages: null,
      orderBy: null,
      orderAsc: false,
      selectedTraining: null,
      trainingToAddParticipants: null,
      trainingToEdit: null,
      filters: [
        {
          text: 'Name',
          metaName: 'title',
          modifier (value) {
            return '%' + value + '%'
          },
          value: null
        },
        {
          text: 'Type',
          metaName: 'is_test_exam',
          value: null,
          options: [
            {
              text: 'Test',
              value: true
            },
            {
              text: 'Non test',
              value: false
            }
          ]
        }
      ],
      alreadyNotified: [],
      excelFields: observervingFields
    }
  },
  computed: {
    trainings () {
      return this.$store.state.observedTrainings
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
      let filters = this.filters.filter(x => x.value !== null).map(f => {
        return {
          name: f.metaName,
          value: f.modifier ? f.modifier(f.value) : f.value
        }
      })
      this.axios.get('/user/observing-trainings', {
        params: {
          page: page,
          filters: filters,
          orderBy: this.orderBy,
          order: this.orderAsc ? 'asc' : 'desc'
        }
      }).then(response => {
        this.isLoading = false
        console.log(response.data)
        this.currentPage = response.data.current_page
        this.totalPages = response.data.last_page
        this.$store.commit('setObservedTrainings', response.data.data)
      }).catch(() => {
        this.isLoading = false
      })
    },
    viewTraining (id) {
      this.selectedTraining = id
    },
    notify (id) {
      if (!this.canNotify(id)) {
        return
      }
      this.axios.patch('trainings/' + id + '/participants')
      this.alreadyNotified.push(id)
    },
    addParticipants (id) {
      this.trainingToAddParticipants = id
    },
    hideAdd (force = false) {
      if (!(force || confirm('Do you want to close window?'))) {
        return
      }
      this.trainingToAddParticipants = null
    },
    hideEdit (force = false) {
      if (!(force || confirm('Do you want to close window?'))) {
        return
      }
      this.trainingToEdit = null
    },
    changePage (page) {
      if (page === this.currentPage) {
        return
      }
      this.loadTrainings(page)
    },
    delayedLoad: lodash.debounce(function () {
      this.loadTrainings()
    }, 400),
    changeOrder (order) {
      if (order === this.orderBy) {
        this.orderAsc ^= true
      } else {
        this.orderBy = order
        this.orderAsc = true
      }
      this.delayedLoad()
    },
    canEditTraining (id) {
      let training = this.trainings.find(t => t.id === id)
      return (training && training.participants) ? !training.participants.some(p => p.status > 1) : true
    },
    canNotify (id) {
      return !this.alreadyNotified.some(x => x === id)
    }
  }
}
</script>
<style>
    .participant-training-header{
        cursor: pointer;
    }
    .participant-training-pagination{
        text-align: center;
    }
</style>
