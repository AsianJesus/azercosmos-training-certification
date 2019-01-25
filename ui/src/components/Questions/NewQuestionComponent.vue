<template>
    <div class="new-question-component">
        <div class="new-question-title">
            <b-form-input type="text" v-model="question" placeholder="Question"
                          :state="showError && verificationErrors.question ? false : null">
            </b-form-input>
        </div>
        <div class="new-question-answers row">
            <div class="col-10">
                <b-form-input v-model="answer1" placeholder="Answer #1"
                              :state="showError && verificationErrors.answer1 ? false : null">
                </b-form-input>
                <b-form-input v-model="answer2" placeholder="Answer #2"
                              :state="showError && verificationErrors.answer2 ? false : null">
                </b-form-input>
                <b-form-input v-model="answer3" placeholder="Answer #3"
                              :state="showError && verificationErrors.answer3 ? false : null">
                </b-form-input>
                <b-form-input v-model="answer4" placeholder="Answer #4"
                              :state="showError && verificationErrors.answer4 ? false : null">
                </b-form-input>
            </div>
            <div class="col">
                <b-form-radio-group stacked v-model="correct_answer"
                                    :state="showError && verificationErrors.correct_answer ? false : null">
                    <b-form-radio :value="0">#1</b-form-radio>
                    <b-form-radio :value="1">#2</b-form-radio>
                    <b-form-radio :value="2">#3</b-form-radio>
                    <b-form-radio :value="3">#4</b-form-radio>
                </b-form-radio-group>
            </div>
        </div>
        <div class="new-question-difficulty">
            <b-form-select :options="difficultiesOptions" v-model="difficulty"
                           :state="showError && verificationErrors.difficulty ? false : null"></b-form-select>
        </div>
        <div class="new-question-file">
            <b-form-file v-model="file" placeholder="Attach file..">

            </b-form-file>
        </div>
        <div class="new-question-buttons">
            <b-btn @click="addQuestion" variant="outline-success">
                Add question
            </b-btn>
        </div>
    </div>
</template>
<script>
import { difficultyOptions } from '../../js/difficulties'
export default{
  data () {
    return {
      question: '',
      answer1: '',
      answer2: '',
      answer3: '',
      answer4: '',
      correct_answer: null,
      difficulty: null,
      file: null,
      showError: false
    }
  },
  computed: {
    verificationErrors () {
      return {
        question: !this.question,
        correct_answer: this.correct_answer === null,
        difficulty: !this.difficulty,
        answer1: !this.answer1,
        answer2: !this.answer2,
        answer3: !this.answer3,
        answer4: !this.answer4
      }
    },
    difficultiesOptions: () => difficultyOptions
  },
  methods: {
    addQuestion () {
      if (Object.values(this.verificationErrors).some(x => x)) {
        this.showError = true
        return
      }
      this.showError = false
      this.$emit('addQuestion', {
        file: this.file,
        question: this.question,
        correct_answer: this.correct_answer,
        difficulty: this.difficulty,
        answer1: this.answer1,
        answer2: this.answer2,
        answer3: this.answer3,
        answer4: this.answer4
      })
      this.question = ''
      this.answer1 = ''
      this.answer2 = ''
      this.answer3 = ''
      this.answer4 = ''
      this.correct_answer = null
      this.difficulty = null
    }
  }
}
</script>
<style>

</style>
