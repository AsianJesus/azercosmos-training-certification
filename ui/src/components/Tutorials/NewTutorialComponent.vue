<template>
    <div class="new-tutorial-component">
        <h5>
            New tutorial
        </h5>
        <div class="new-tutorial-component form">
            <b-form-input placeholder="Title" v-model="form.title"></b-form-input>
            <b-form-select :options="systems" placeholder="System" v-model="form.system"></b-form-select>
            <div class="new-tutorial-component questions-list">
                    <table class="table table-hover">
                        <tr>
                            <th>Question</th>
                            <th>Answers</th>
                            <th>Correct</th>
                            <th>Difficulty</th>
                        </tr>
                        <tr v-for="(question, index) in form.questions" v-bind:key="index">
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
                <hr>
                <div class="new-tutorial-component new-question">
                    <div class="new-question-title">
                        <input type="text" v-model="newQuestion.question" placeholder="Question">
                    </div>
                    <div class="new-question-answers">
                        <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="0">
                        <input type="text" v-model="newQuestion.answer1" placeholder="Answer">
                        <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="1">
                        <input type="text" v-model="newQuestion.answer2" placeholder="Answer">
                        <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="2">
                        <input type="text" v-model="newQuestion.answer3" placeholder="Answer">
                        <input type="radio" name="new-question-correct" v-model="newQuestion.correct_answer" :value="3">
                        <input type="text" v-model="newQuestion.answer4" placeholder="Answer">
                    </div>
                    <div class="new-question-difficulty">
                        <b-form-select :options="difficultiesOptions" v-model="newQuestion.difficulty"></b-form-select>
                    </div>
                    <div class="new-question-buttons">
                        <b-btn @click="addQuestion" variant="outline-success">
                            Add question
                        </b-btn>
                    </div>
                </div>
            </div>
            <div class="new-tutorial-component-buttons">
                <b-btn variant="outline-success" @click="createTutorial">
                    Create tutorial
                </b-btn>
            </div>
        </div>
    </div>
</template>
<script>
const systems = [
  {
    text: 'Please, select system',
    value: null
  },
  {
    text: 'AZERSKY',
    value: 'azersky'
  }
]
const difficulties = {
  1: 'Easy',
  2: 'Medium',
  3: 'Hard'
}
export default{
  data () {
    return {
      systems: systems,
      form: {
        title: null,
        system: null,
        questions: [],
        author: 1
      },
      newQuestion: {
        question: '',
        answer1: '',
        answer2: '',
        answer3: '',
        answer4: '',
        correct_answer: '',
        difficulty: null,
        verified: true,
        author_id: 1
      }
    }
  },
  computed: {
    canAddQuestion () {
      for (let key in this.newQuestionErrors) {
        if (this.newQuestionErrors[key]) {
          return false
        }
      }
      return true
    },
    canCreateTutorial () {
      for (let key in this.tutorialErrors) {
        if (this.tutorialErrors[key]) {
          return false
        }
      }
      return true
    },
    newQuestionErrors () {
      return {
        question: !this.newQuestion.question,
        answer1: !this.newQuestion.answer1,
        answer2: !this.newQuestion.answer2,
        answer3: !this.newQuestion.answer3,
        answer4: !this.newQuestion.answer4,
        difficulty: !this.newQuestion.difficulty,
        correct_answer: !this.newQuestion.correct_answer
      }
    },
    tutorialErrors () {
      return {
        title: !this.form.title,
        system: !this.form.system,
        question: !this.form.questions.length
      }
    },
    difficultiesOptions () {
      let result = Object.keys(difficulties).map((x) => {
        return {
          text: difficulties[x],
          value: parseInt(x)
        }
      })
      result.unshift({
        text: 'Please, select difficulty',
        value: null
      })
      return result
    },
    userID () {
      return 1
    }
  },
  methods: {
    addQuestion () {
      if (!this.canAddQuestion) {
        alert('Cannot add question')
        return
      }
      this.form.questions.push(this.newQuestion)
      this.newQuestion = {
        question: '',
        answer1: '',
        answer2: '',
        answer3: '',
        answer4: '',
        correct_answer: '',
        difficulty: null,
        verified: true
      }
    },
    mapDifficulty (id) {
      return difficulties[id]
    },
    createTutorial () {
      if (!this.canCreateTutorial) {
        alert('Can\'t create tutorial')
        return
      }
      this.axios.post('/tutorials', this.form).then(response => {
        alert('Tutorial was successfully created')
        this.$store.commit('addTutorial', response.data)
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
