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
                <div class="new-question-title">
                    <input type="text" v-model="newQuestion.question" placeholder="Question"
                           :class="questionErrors.question && showError ? 'error-outline' : ''">
                </div>
                <div class="new-question-answers">
                    <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="0"
                           :class="questionErrors.correct_answer && showError ? 'error-border' : ''">
                    <input type="text" v-model="newQuestion.answer1" placeholder="Answer"
                           :class="questionErrors.answer1 && showError ? 'error-border' : ''">
                    <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="1"
                           :class="questionErrors.correct_answer && showError ? 'error-border' : ''">
                    <input type="text" v-model="newQuestion.answer2" placeholder="Answer"
                           :class="questionErrors.answer2 && showError ? 'error-border' : ''">
                    <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="2"
                           :class="questionErrors.correct_answer && showError ? 'error-border' : ''">
                    <input type="text" v-model="newQuestion.answer3" placeholder="Answer"
                           :class="questionErrors.answer3 && showError ? 'error-border' : ''">
                    <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="3"
                           :class="questionErrors.correct_answer && showError ? 'error-border' : ''">
                    <input type="text" v-model="newQuestion.answer4" placeholder="Answer"
                           :class="questionErrors.answer4 && showError ? 'error-border' : ''">
                </div>
                <div class="new-question-difficulty">
                    <b-form-select :options="difficultyOptions" v-model="newQuestion.difficulty"
                                   :state="(showError && questionErrors.difficulty) ? false : null">

                    </b-form-select>
                </div>
                <div class="new-question-buttons">
                    <b-btn @click="addQuestion" variant="outline-success">
                        Add question
                    </b-btn>
                </div>
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
  computed: {
    difficultyOptions () {
      return difficultyOptions
    },
    questionErrors () {
      return {
        question: !this.newQuestion.question,
        answer1: !this.newQuestion.answer1,
        answer2: !this.newQuestion.answer2,
        answer3: !this.newQuestion.answer3,
        answer4: !this.newQuestion.answer4,
        correct_answer: this.newQuestion.correct_answer === null,
        difficulty: !this.newQuestion.difficulty
      }
    }

  },
  data () {
    return {
      questions: [],
      newQuestion: {
        author_id: 1,
        question: '',
        answer1: '',
        answer2: '',
        answer3: '',
        answer4: '',
        correct_answer: null,
        verified: this.moderator,
        difficulty: null,
        tutorial_id: this.tutorial.id
      },
      showError: false
    }
  },
  methods: {
    mapDifficulty: mapDifficulty,
    addQuestion () {
      if (Object.values(this.questionErrors).some(x => x)) {
        alert('Error ')
        this.showError = true
        return
      }
      this.showError = false
      this.questions.push(this.newQuestion)
      this.newQuestion = {
        author_id: 1,
        question: '',
        answer1: '',
        answer2: '',
        answer3: '',
        answer4: '',
        correct_answer: null,
        verified: this.moderator,
        difficulty: null,
        tutorial_id: this.tutorial.id
      }
    },
    postQuestions () {
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
        this.$emit('close')
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
