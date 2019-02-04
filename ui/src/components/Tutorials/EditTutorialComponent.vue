<template>
    <div class="edit-tutorial-component">
        <div class="edit-tutorial-headline">
            <span class="edit-tutorial-id">
                Tutorial #{{ editedTutorial.id }}
            </span>
        </div>
        <div class="edit-tutorial-body">
            <div class="edit-tutorial-row row-margin">
                <div class="edit-tutorial-title col-6">
                    Title
                    <b-form-input v-model="editedTutorial.title" placeholder="Title">

                    </b-form-input>
                </div>
                <div class="edit-tutorial-system col-6">
                    System
                    <b-form-select :options="systemOptions" v-model="editedTutorial.system">

                    </b-form-select>
                </div>
            </div>
            <div class="edit-tutorial-moderators row-margin">
                <div class="edit-tutorial-moderators col-6">
                    Moderators:
                    <multi-select :options="usersOptions" :selected-options="moderators"
                                  @select="moderators = $event" placeholder="Moderators">

                    </multi-select>
                </div>
                <div class="edit-tutorial-observers col-6">
                    Observers:
                    <multi-select :options="usersOptions" :selected-options="observers"
                                  @select="observers = $event" placeholder="Observers">

                    </multi-select>
                </div>
            </div>
            <div class="edit-tutorial-questions" v-if="questions && questions.length">
                <details>
                    <summary>Questions</summary>
                    <div class="edit-tutorial-questions-list">
                        <div v-for="(q, i) in questions" v-bind:key="i" class="edit-tutorial-question">
                            <editable-question :question="q" :isDeleted="isDeleting(q.id)" @undoDelete="undoDeleteQuestion(q.id)"
                                               @deleteQuestion="deleteQuestion(q.id)" @saveEdit="editQuestion(q.id, $event)">

                            </editable-question>
                        </div>
                    </div>
                </details>
            </div>
        </div>
        <div class="edit-tutorial-buttons">
            <b-btn @click="saveEdit" variant="outline-success">
                Save
            </b-btn>
            <b-btn variant="outline-danger" @click="$emit('close', true)">
                Close
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
<style  >
.edit-tutorial-headline{
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #30303080;
}
.edit-tutorial-id{
    padding: .4rem .6rem;
    border: 1px solid #30303040;
    border-radius: 5px;
    font-size: 1.2rem;
}
.edit-tutorial-body{
    text-align: left;
}
.edit-tutorial-row, .edit-tutorial-questions, .edit-tutorial-question{
    margin-top: .6rem;
    margin-bottom: .6rem;
}
.edit-tutorial-buttons{
    margin: .6rem auto;
    text-align: right;
}
</style>
