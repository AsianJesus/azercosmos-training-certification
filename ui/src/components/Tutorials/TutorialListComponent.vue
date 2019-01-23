<template>
    <div class="tutorial-list-component">
        <h4>Tutorials</h4>
        <b-tabs>
            <b-tab title="My tutorials">
                <table class="table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Questions</th>
                        <th>System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in MyTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
                            <b-btn variant="outline-success">Verify</b-btn>
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
                        <th>ID</th>
                        <th>Title</th>
                        <th>Questions</th>
                        <th>System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in ModeratingTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
                            <b-btn variant="outline-primary">Add questions</b-btn>
                            <b-btn variant="outline-success">Verify</b-btn>
                            <b-btn variant="outline-secondary">Edit</b-btn>
                        </td>
                    </tr>
                </table>
            </b-tab>
            <b-tab title="Observing tutorials">
                <table class="table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Questions</th>
                        <th>System</th>
                        <th>Controls</th>
                    </tr>
                    <tr v-for="(t, index) in ObservingTutorials" v-bind:key="index" v-if="t">
                        <td>{{ t.id }}</td>
                        <td>{{ t.title }}</td>
                        <td>{{ t.verified_questions_count }} / {{ t.questions_count }}</td>
                        <td>{{ t.system }}</td>
                        <td>
                            <b-btn variant="outline-success" @click="selectedTutorial.view = t">View</b-btn>
                            <b-btn variant="outline-primary">Add questions</b-btn>
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
    </div>
</template>
<script>
import ViewTutorialComponent from './ViewTutorialComponent.vue'
import AddQuestionsComponent from './AddQuestionsComponent.vue'
import EditTutorialComponent from './EditTutorialComponent.vue'
export default{
  components: {
    ViewTutorialComponent,
    AddQuestionsComponent,
    EditTutorialComponent
  },
  computed: {
    MyTutorials () {
      return this.$store.state.tutorials['my']
    },
    ModeratingTutorials () {
      return this.$store.state.tutorials['moderating']
    },
    ObservingTutorials () {
      return this.$store.state.tutorials['observing']
    }
  },
  data () {
    return {
      selectedTutorial: {
        view: null,
        addQuestions: null,
        edit: null
      }
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
    canAddQuestion (id) {
      return this.MyTutorials.filter(x => x.id === id).length > 0 ||
          this.ModeratingTutorials.filter(x => x.id === id).length > 0
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
    }
  }
}
</script>
<style>

</style>
