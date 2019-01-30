<template>
    <div class="tutorial-list-component">
        <h4>Tutorials</h4>
        <filter-component v-model="filters"></filter-component>
        <b-tabs>
            <b-tab title="My tutorials">
                <table class="table table-hover">
                    <tr>
                        <th class="order-button" @click="sort('id')">ID</th>
                        <th class="order-button" @click="sort('title')">Title</th>
                        <th class="order-button" @click="sort('questions_count')">Questions</th>
                        <th class="order-button" @click="sort('system')">System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in MyTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
<!--
                            <b-btn variant="outline-success" @click="selectedTutorial.verifyQuestions = t">Verify</b-btn>
-->
                            <b-btn variant="outline-primary" @click="selectedTutorial.addQuestions = t">Add question</b-btn>
                            <b-btn variant="outline-secondary" @click="selectedTutorial.edit = t">Edit</b-btn>
                            <b-btn variant="outline-danger" @click="deleteTutorial(t.id)">Delete</b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
            <b-tab title="Moderating tutorials">
                <table class="table table-hover">
                    <tr>
                        <th class="order-button" @click="sort('id')">ID</th>
                        <th class="order-button" @click="sort('title')">Title</th>
                        <th class="order-button" @click="sort('questions_count')">Questions</th>
                        <th class="order-button" @click="sort('system')">System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in ModeratingTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
                            <b-btn variant="outline-primary" @click="selectedTutorial.addQuestions = t">Add questions</b-btn>
                            <b-btn variant="outline-success" @click="selectedTutorial.verifyQuestions = t">Verify</b-btn>
                            <b-btn variant="outline-secondary" @click="selectedTutorial.edit = t">Edit</b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
            <b-tab title="Observing tutorials">
                <table class="table table-hover">
                    <tr>
                        <th class="order-button" @click="sort('id')">ID</th>
                        <th class="order-button" @click="sort('title')">Title</th>
                        <th class="order-button" @click="sort('questions_count')">Questions</th>
                        <th class="order-button" @click="sort('system')">System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in ObservingTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
                            <b-btn variant="outline-primary" @click="selectedTutorial.addQuestions = t">Add questions</b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
        </b-tabs>
        <transition name="fade">
            <div class="modal-window-canvas" @click="hideView" v-if="selectedTutorial.view">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <view-tutorial-component :tutorial="selectedTutorial.view" @close="hideView">

                    </view-tutorial-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="selectedTutorial.addQuestions" @click="hideAddQuestions()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <add-questions-component :tutorial="selectedTutorial.addQuestions" @close="hideAddQuestions($event)"
                                             :moderator="canAddQuestion(selectedTutorial.addQuestions.id)">

                    </add-questions-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="selectedTutorial.edit" @click="hideEdit()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <edit-tutorial-component :tutorial="selectedTutorial.edit" @close="hideEdit($event)">

                    </edit-tutorial-component>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="selectedTutorial.verifyQuestions" @click="hideVerify()">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
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
      ]
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
<style>

</style>
