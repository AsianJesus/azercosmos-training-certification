<template>
    <div class="add-questions-component">
        <div class="add-questions-title">
            <h4>
                {{ tutorial.title }}
            </h4>
            <b-btn @click="$emit('close', true)" variant="outline-danger">
                Close
            </b-btn>
        </div>
        <div class="add-questions-body">
            <div class="add-questions-list">
                <table class="table table-hover">
                        <tr>
                            <th>Question</th>
                            <th>Answers</th>
                            <th>Correct</th>
                            <th>Difficulty</th>
                        </tr>
                        <tr v-for="(question, index) in questions" v-bind:key="index">
                            <td>{{ question.question }}</td>
                            <td>
                                <div>
                                    <span>1) {{ question.answer1 }}</span>
                                </div>
                                <div>
                                    <span>2) {{ question.answer2 }}</span>
                                </div>
                                <div>
                                    <span>3) {{ question.answer3 }}</span>
                                </div>
                                <div>
                                    <span>4) {{ question.answer4 }}</span>
                                </div>
                            </td>
                            <td>{{ question.correct_answer + 1}}</td>
                            <td>{{ mapDifficulty(question.difficulty) }}</td>
                        </tr>
                </table>
            </div>
            <hr>
            <div class="add-questions-new-question">
                <new-question-component @addQuestion="addQuestion"></new-question-component>
            </div>
        </div>
        <hr>
        <div class="add-questions-post-button">
            <b-btn @click="postQuestions" variant="outline-primary">
                Post questions
            </b-btn>
        </div>
    </div>
</template>
<script>
import { mapDifficulty, difficultyOptions } from '../../js/difficulties'
import NewQuestionComponent from '../Questions/NewQuestionComponent.vue'
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
    NewQuestionComponent
  },
  data () {
    return {
      questions: []
    }
  },
  methods: {
    mapDifficulty: mapDifficulty,
    addQuestion (question) {
      question.author_id = this.$store.state.userID
      question.verified = this.moderator
      question.tutorial_id = this.tutorial.id
      this.questions.push(question)
    },
    postQuestions () {
      var form = new FormData()
      this.questions.forEach((q, index) => {
        Object.keys(q).forEach((key) => {
          form.set('questions['+index+']['+key+']', q[key])
        })
        form.append('file' + index, q.file)
      })
      this.axios.post('/questions', {
          questions: this.questions
      }).then(response => {
        let props = {}
        props['questions_count'] = this.tutorial.questions_count + response.data.length
        if (this.moderator) {
          props['verified_questions_count'] = this.tutorial.verified_questions_count + response.data.length
        }
        this.$store.commit('updateTutorial', {
          id: this.tutorial.id,
          props: props
        })
        setTimeout(() => this.$emit('close'), 1000);
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
