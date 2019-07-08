<template>
    <div class="tutorial-list-component">
        <filter-component v-model="filters"></filter-component>
        <b-tabs>
            <b-tab title="My tutorials">
                <div class="excel-button-holder">
                    <download-excel :fields="excelFields" :data="MyTutorials" style="display: inline;">
                        <b-btn variant="outline-success"
                               class="excel-button"
                        >
                            <v-icon name="file-excel" />
                        </b-btn>
                    </download-excel>
                </div>
                <table class="table training-table">
                    <tr>
                        <th class="order-button" @click="sort('id')">
                            ID
                            <span v-if="orderBy === 'id'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('title')">
                            Title
                            <span v-if="orderBy === 'title'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('questions_count')">
                            Questions
                            <span v-if="orderBy === 'questions_count'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('system')">
                            System
                            <span v-if="orderBy === 'system'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th @click="$event.stopPropagation()"></th>
                    </tr>
                    <tr v-for="(t, index) in MyTutorials" v-bind:key="index" v-if="t" style="cursor: pointer;"
                        @click="selectedTutorial.view = t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td class="control-buttons" @click="$event.stopPropagation()">
<!--
                            <b-btn variant="outline-success" @click="selectedTutorial.verifyQuestions = t">Verify</b-btn>
-->
                            <b-btn variant="outline-primary" class="add-questions-button" @click="selectedTutorial.addQuestions = t">
                                <v-icon name="plus" />
                            </b-btn>
                            <b-btn variant="outline-secondary" class="edit-button" @click="selectedTutorial.edit = t">
                                <v-icon name="pen" />
                            </b-btn>
                            <b-btn variant="outline-danger" class="delete-button" @click="deleteTutorial(t.id)">
                                <v-icon name="trash" />
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
            <b-tab title="Moderating tutorials">
                <div class="excel-button-holder">
                    <download-excel :fields="excelFields" :data="ModeratingTutorials" style="display: inline;">
                        <b-btn variant="outline-success"
                               class="excel-button" >
                            <v-icon name="file-excel" />
                        </b-btn>
                    </download-excel>
                </div>
                <table class="table training-table">
                    <tr>
                        <th class="order-button" @click="sort('id')">
                            ID
                            <span v-if="orderBy === 'id'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('title')">
                            Title
                            <span v-if="orderBy === 'title'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('questions_count')">
                            Questions
                            <span v-if="orderBy === 'questions_count'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('system')">
                            System
                            <span v-if="orderBy === 'system'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th></th>
                    </tr>
                    <tr v-for="(t, index) in ModeratingTutorials" v-bind:key="index" v-if="t" style="cursor: pointer;"
                        @click="selectedTutorial.view = t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td class="control-buttons" @click="$event.stopPropagation()">
                            <b-btn variant="outline-primary" class="add-questions-button" @click="selectedTutorial.addQuestions = t">
                                <v-icon name="plus" />
                            </b-btn>
                            <b-btn variant="outline-success"
                                   class="check-button"
                                   @click="selectedTutorial.verifyQuestions = t">
                                <v-icon name="check"></v-icon>
                            </b-btn>
                            <b-btn variant="outline-secondary"
                                   class="edit-button"
                                   @click="selectedTutorial.edit = t">
                                <v-icon name="pen"></v-icon>
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
            <b-tab title="Observing tutorials">
                <div style="text-align: right; margin: 1rem  0;">
                    <download-excel :fields="excelFields" :data="ObservingTutorials" style="display: inline;">
                        <b-btn variant="outline-success"
                               class="excel-button" >
                            <v-icon name="file-excel" />
                        </b-btn>
                    </download-excel>
                </div>
                <table class="table training-table">
                    <tr>
                        <th class="order-button" @click="sort('id')">
                            ID
                            <span v-if="orderBy === 'id'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('title')">
                            Title
                            <span v-if="orderBy === 'title'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('questions_count')">
                            Questions
                            <span v-if="orderBy === 'questions_count'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th class="order-button" @click="sort('system')">
                            System
                            <span v-if="orderBy === 'system'">
                                <v-icon name="angle-up" v-if="orderAsc">

                                </v-icon>
                                <v-icon name="angle-down" v-else>

                                </v-icon>
                            </span>
                        </th>
                        <th>    </th>
                    </tr>
                    <tr v-for="(t, index) in ObservingTutorials" v-bind:key="index" v-if="t" style="cursor: pointer;"
                        @click="selectedTutorial.view = t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td class="control-buttons" @click="$event.stopPropagation()">
                            <b-btn variant="outline-primary" class="add-questions-button" @click="selectedTutorial.addQuestions = t">
                                <v-icon name="plus" />
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
        </b-tabs>
        <transition name="fade">
            <div class="modal-window-canvas"
                 @click="hideView"
                 v-if="selectedTutorial.view">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="hideView"> X </div>
                    <view-tutorial-component :tutorial="selectedTutorial.view" @close="hideView">

                    </view-tutorial-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas"
                 v-if="selectedTutorial.addQuestions"
                 @click="hideAddQuestions()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="hideAddQuestions(true)"> X </div>
                    <add-questions-component :tutorial="selectedTutorial.addQuestions" @close="hideAddQuestions($event)"
                                             :moderator="canAddQuestion(selectedTutorial.addQuestions.id)">

                    </add-questions-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas"
                 v-if="selectedTutorial.edit"
                 @click="hideEdit()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="hideEdit(true)"> X </div>
                    <edit-tutorial-component :tutorial="selectedTutorial.edit" @close="hideEdit($event)">

                    </edit-tutorial-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas"
                 v-if="selectedTutorial.verifyQuestions"
                 @click="hideVerify()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="hideVerify(true)"> X </div>
                    <verify-questions :tutorial="selectedTutorial.verifyQuestions" @close="hideVerify($event)">
                    </verify-questions>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import ViewTutorialComponent from './ViewTutorialComponent.vue'
import AddQuestionsComponent from './AddQuestionsComponent.vue'
import EditTutorialComponent from './EditTutorialComponent.vue'
import VerifyQuestions from './VerifyQuestions.vue'
import FilterComponent from '../Utilities/FilterComponent.vue'
import { tutorialFields } from '../../js/excelData'
export default{
  components: {
    ViewTutorialComponent,
    AddQuestionsComponent,
    EditTutorialComponent,
    VerifyQuestions,
    FilterComponent
  },
  computed: {
    MyTutorials () {
      return this.orderTutorials(this.filterTutorials(this.$store.state.tutorials['my']))
    },
    ModeratingTutorials () {
      return this.orderTutorials(this.filterTutorials(this.$store.state.tutorials['moderating']))
    },
    ObservingTutorials () {
      return this.orderTutorials(this.filterTutorials(this.$store.state.tutorials['observing']))
    }
  },
  data () {
    return {
      selectedTutorial: {
        view: null,
        addQuestions: null,
        edit: null,
        verifyQuestions: null
      },
      orderBy: null,
      orderAsc: null,
      filters: [
        {
          text: 'Name',
          meta: {
            name: 'title',
            doesMatch (value, toMatch) {
              return value ? toMatch.toString().toLowerCase().includes(value.toLowerCase()) : true
            }
          },
          value: null
        },
        {
          text: 'System',
          meta: {
            name: 'system',
            doesMatch (value, toMatch) {
              return value ? toMatch.toString().toLowerCase().includes(value.toLowerCase()) : true
            }
          },
          value: null
        },
        {
          text: 'Max questions',
          meta: {
            name: 'questions_count',
            doesMatch (value, toMatch) {
              return (value && !isNaN(value)) ? parseInt(value) >= toMatch : true
            }
          },
          value: null
        },
        {
          text: 'Min questions',
          meta: {
            name: 'questions_count',
            doesMatch (value, toMatch) {
              return (value && !isNaN(value)) ? parseInt(value) <= toMatch : true
            }
          },
          value: null
        },
        {
          text: 'Min verified questions',
          meta: {
            name: 'verified_questions_count',
            doesMatch (value, toMatch) {
              return (value && !isNaN(value)) ? parseInt(value) <= toMatch : true
            }
          },
          value: null
        },
        {
          text: 'Max verified questions',
          meta: {
            name: 'verified_questions_count',
            doesMatch (value, toMatch) {
              return (value && !isNaN(value)) ? parseInt(value) >= toMatch : true
            }
          },
          value: null
        }
      ],
      excelFields: tutorialFields
    }
  },
  methods: {
    hideView () {
      this.selectedTutorial.view = null
    },
    hideAddQuestions (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.selectedTutorial.addQuestions = null
    },
    hideEdit (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.selectedTutorial.edit = null
    },
    hideVerify (force = false) {
      if (!force && !confirm('Do you wanna close window?')) {
        return
      }
      this.selectedTutorial.verifyQuestions = null
    },
    canAddQuestion (id) {
      return this.ModeratingTutorials.some(x => x.id === id)
    },
    deleteTutorial (id) {
      if (!confirm('Do you wanna delete tutorial #' + id)) {
        return
      }
      this.axios.delete('tutorials/' + id).then(() => {
        this.$store.commit('deleteTutorial', id)
      }).catch(err => {
        alert('Some error occurred')
        console.log(err)
      })
    },
    filterTutorials (tutorials) {
      return tutorials.filter(t => {
        return !this.filters.some(f => !f.meta.doesMatch(f.value, t[f.meta.name]))
      })
    },
    orderTutorials (tutorials) {
      if (this.orderBy) {
        let asc = this.orderAsc
        let o = this.orderBy
        return tutorials.sort((a, b) => (asc ? -1 : 1) * (a[o] > b[o] ? 1 : (a[o] === b[o] ? 0 : -1)))
      } else {
        return tutorials
      }
    },
    sort (order) {
      if (this.orderBy === order) {
        this.orderAsc ^= true
      } else {
        this.orderAsc = true
        this.orderBy = order
      }
    }
  }
}
</script>
<style lang="sass">

.excel-button-holder
    text-align: right
    margin: 1rem auto

</style>
