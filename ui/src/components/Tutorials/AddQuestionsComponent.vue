<template>
    <div class="add-questions-component">
        <div class="add-questions-title">
            <h4>
                {{ tutorial.title }}
            </h4>
        </div>
        <div class="add-questions-body">
            <div class="add-questions-list">
               <h5>
                    Questions
                </h5>
                <div v-for="(q, i) in questions" v-bind:key="i">
                    <editable-question :question="q"
                                       :showAuthor="false"
                                       :showFile="false"
                                       @saveEdit="editQuestion(i, $event)"
                                       @deleteQuestion="deleteQuestion(i)" />
                </div>
            </div>
            <div class="add-questions-new-question">
                <new-question-component @addQuestion="addQuestion" />
            </div>
        </div>
        <div class="add-questions-error" v-if="errorMessage">
            <h6>
                {{ errorMessage }}
            </h6>
        </div>
        <div class="add-questions-buttons">
            <transition name="fade">
                <b-btn @click="postQuestions" variant="outline-primary" class="edit-button" v-if="questions.length">
                    <v-icon name="paper-plane" />
                </b-btn>
            </transition>
            <b-btn @click="$emit('close', true)" variant="outline-danger" class="edit-button">
                <v-icon name="ban" />
            </b-btn>
        </div>
    </div>
</template>
<script>
import { mapDifficulty } from '../../js/difficulties'
import NewQuestionComponent from '../Questions/NewQuestionComponent.vue'
import EditableQuestion from '../Questions/EditableQuestion.vue'
export default{
  props: {
    tutorial: {
      type: Object,
      required: true
    },
    moderator: {
      type: Boolean,
      default: false
    }
  },
  components: {
    NewQuestionComponent,
    EditableQuestion
  },
  data () {
    return {
      questions: [],
      errorMessage: ''
    }
  },
  methods: {
    mapDifficulty: mapDifficulty,
    addQuestion (question) {
      question.verified = this.moderator
      question.tutorial_id = this.tutorial.id
      this.questions.push(question)
    },
    postQuestions () {
      if (!this.questions.length) {
        this.errorMessage = 'There are no questions to add'
        return
      }
      let form = new FormData()
      this.questions.forEach((q, index) => {
        Object.keys(q).forEach((key) => {
          if (key !== 'file') {
            form.set('questions[' + index + '][' + key + ']', q[key])
          } else {
            form.set('file' + index, q[key])
          }
        })
      })
      this.axios.post('/questions', form).then(response => {
        let props = {}
        props['questions_count'] = this.tutorial.questions_count + response.data.length
        if (this.moderator) {
          props['verified_questions_count'] = this.tutorial.verified_questions_count + response.data.length
        }
        console.log(props)
        this.$store.commit('updateTutorial', {
          id: this.tutorial.id,
          props: props
        })
        setTimeout(() => this.$emit('close', true), 1000)
      }).catch(err => {
        alert('Some error occurred')
        console.log(err)
      })
    },
    editQuestion (i, question) {
      this.$set(this.questions, i, question)
    },
    deleteQuestion (i) {
      this.questions.splice(i, 1)
    }
  }
}
</script>
<style>
.add-questions-title{
    border-bottom: 1px solid #30303060;
}
.add-questions-component{
    text-align: left;
}
.add-questions-title{
    text-align: center;
}
.add-questions-title, .add-questions-list, .add-questions-new-question {
    margin-bottom: 1.2rem;
}
.add-questions-buttons{
    text-align: right;
}
</style>
