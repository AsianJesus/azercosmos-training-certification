<template>
    <div class="edit-tutorial-component">
        <div class="edit-tutorial-close">
            <b-btn variant="outline-danger" @click="$emit('close', true)">
                Close
            </b-btn>
        </div>
        <div class="edit-tutorial-title">
            <b-form-input v-model="editedTutorial.title" placeholder="Title">

            </b-form-input>
        </div>
        <div class="edit-tutorial-system">
            <b-form-select :options="systemOptions" v-model="editedTutorial.system">

            </b-form-select>
        </div>
        <div class="edit-tutorial-moderators">
            Moderators:
            <multi-select :options="usersOptions" :selected-options="moderators"
                          @select="moderators = $event" placeholder="Moderators">

            </multi-select>
        </div>
        <div class="edit-tutorial-observers">
            Observers:
            <multi-select :options="usersOptions" :selected-options="observers"
                          @select="observers = $event" placeholder="Observers">

            </multi-select>
        </div>
        <div class="edit-tutorial-questions">
            <details>
                <summary>Questions</summary>
                <div class="edit-tutorial-questions-list">
                    <div v-for="(q, i) in questions" v-bind:key="i">
                        <editable-question :question="q" :isDeleted="isDeleting(q.id)" @undoDelete="undoDeleteQuestion(q.id)"
                                           @deleteQuestion="deleteQuestion(q.id)" @saveEdit="editQuestion(q.id, $event)">

                        </editable-question>
                    </div>
                </div>
            </details>
        </div>
        <div class="edit-tutorial-buttons">
            <b-btn @click="saveEdit" variant="outline-success">
                Save
            </b-btn>
        </div>
    </div>
</template>
<script>
import { MultiSelect } from 'vue-search-select'
import { systemOptions } from '../../js/systems'
import EditableQuestion from '../Questions/EditableQuestion.vue'
export default{
  components: {
    MultiSelect,
    EditableQuestion
  },
  props: {
    tutorial: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      editedTutorial: {},
      moderators: [],
      observers: [],
      questions: [],
      editedQuestions: {},
      questionsToDelete: []
    }
  },
  computed: {
    usersOptions () {
      return this.$store.getters.usersOptions
    },
    systemOptions: () => systemOptions
  },
  mounted () {
    this.loadQuestions()
    this.editedTutorial = JSON.parse(JSON.stringify(this.tutorial))
    this.moderators = this.tutorial.moderators.map(u => {
      return {
        text: u.user.NAME,
        value: u.moderator_id
      }
    })
    this.observers = this.tutorial.observers.map(u => {
      return {
        text: u.user.NAME,
        value: u.observer_id
      }
    })
  },
  methods: {
    saveEdit () {
      let form = this.editedTutorial
      form.moderators = this.moderators.map(x => x.value)
      form.observers = this.observers.map(x => x.value)
      this.axios.delete('/questions', {
        params: {
          ids: this.questionsToDelete
        }
      })
      this.axios.patch('/questions', this.editedQuestions)
      this.axios.put('/tutorials/' + this.tutorial.id, form).then(response => {
        this.$store.commit('updateTutorial', {
          id: response.data.id,
          props: response.data
        })
      })
      this.$emit('close', true)
    },
    loadQuestions () {
      this.axios.get('/tutorials/' + this.tutorial.id + '/questions').then(response => {
        this.questions = response.data.verified
      })
    },
    isDeleting (id) {
      return this.questionsToDelete.some(x => x === id)
    },
    deleteQuestion (id) {
      this.questionsToDelete.push(id)
    },
    undoDeleteQuestion (id) {
      this.questionsToDelete = this.questionsToDelete.filter(x => x !== id)
    },
    editQuestion (id, question) {
      question.author_id = this.$store.state.userID
      this.editedQuestions[question.id] = question
      for (let i = 0; i < this.questions.length; i++) {
        if (this.questions[i].id === id) {
          this.$set(this.questions, i, question)
        }
      }
    }
  }

}
</script>
<style>

</style>
