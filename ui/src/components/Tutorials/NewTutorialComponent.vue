<template>
    <div class="new-tutorial-component">
        <h5>
            New tutorial
            <b-btn variant="outline-warning" @click="$emit('close', true)">Close</b-btn>
        </h5>
        <div class="new-tutorial-component form">
            <b-form-input placeholder="Title" v-model="form.title"></b-form-input>
            <b-form-select :options="systems" placeholder="System" v-model="form.system"></b-form-select>
            <multi-select :options="usersOptions" :selected-options="moderators" @select="moderators = $event"
                          placeholder="Moderators">
            </multi-select>
            <multi-select :options="usersOptions" :selected-options="observers" @select="observers = $event"
                        placeholder="Observers">
            </multi-select>
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
import { MultiSelect } from 'vue-search-select'
import { mapDifficulty, difficultyOptions } from '../../js/difficulties'
import { systemOptions } from '../../js/systems'

export default{
  components: {
    MultiSelect
  },
  data () {
    return {
      form: {
        title: null,
        system: null,
        questions: [],
        author: 1
      },
      moderators: [],
      observers: [],
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
        return difficultyOptions
    },
    userID () {
      return 1
    },
    usersOptions () {
      return this.$store.getters.usersOptions
    },
    systems: () => systemOptions
  },
  methods: {
    addQuestion () {
      if (!this.canAddQuestion) {
        alert('Cannot add question')
        return
      }
      this.newQuestion.author_id = 1
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
    mapDifficulty: mapDifficulty,
    createTutorial () {
      if (!this.canCreateTutorial) {
        alert('Can\'t create tutorial')
        return
      }
      this.form.moderators = this.moderators.map(x => {
        return {
          moderator_id: x.value
        }
      })
      this.form.observers = this.observers.map(x => {
        return {
          observer_id: x.value
        }
      })
      this.axios.post('/tutorials', this.form).then(response => {
        alert('Tutorial was successfully created')
        this.$store.commit('addTutorial', {
            type: 'my',
            tutorial: response.data
        })
        if (this.observers.filter(x => x.value === 1)) {
          this.$store.commit('addTutorial', {
            type: 'observing',
            tutorial: response.data
          })
        }
        if (this.moderating.filter(x => x.value === 1)) {
          this.$store.commit('addTutorial', {
            type: 'moderating',
            tutorial: response.data
          })
        }
        this.$emit('close', true)
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
