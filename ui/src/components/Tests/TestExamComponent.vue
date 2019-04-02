<template>
    <div class="test-exam-component">
        <div class="test-exam-not-loaded" v-if="!isAllLoaded">
            <div v-if="isTestExam === null">
                <h4>
                    Questions are not loaded yet
                </h4>
            </div>
            <div v-else-if="isTestExam === false">
                <h4>
                    Sorry, but it is not test training
                </h4>
            </div>
            <div v-else>
                <h4>
                    Questions are not loaded yet
                </h4>
            </div>
        </div>
        <div class="test-exam-start" v-else-if="!isStarted">
            <div class="start-training-name">
                {{ trainingName }}
            </div>
            <div class="start-questions-count">
                {{ questions.length }} questions
            </div>
            <div class="row-margin">
                <div class="col-6 start-info-header">Success threshold: </div>
                <div class="col-6 start-info">{{ passScore }}%</div>
                <div class="col-6 start-info-header">Deadline: </div>
                <div class="col-6 start-info"> {{ participant.end_date }}</div>
            </div>
            <div class="start-controls">
                <b-btn variant="outline-primary" @click="startExam">
                    Start
                </b-btn>
                <b-btn  variant="outline-danger"
                        @click="$emit('close', true)" >
                    Cancel
                </b-btn>
            </div>
        </div>
        <div class="test-exam-loaded row" v-else>
            <div class="test-exam-header col-12">
                <div class="row" style="text-align: left;">
                    <div class="col-3" @click="passExam(); $emit('close', true)" style="cursor: pointer;">
                        <v-icon name="arrow-left" :scale="1.5" />
                    </div>
                    <div class="col-9 test-exam-name">
                        {{ trainingName }} <span v-if="tutorialName"> | </span>{{ tutorialName }}
                    </div>
                    <div class="col-9 offset-3">
                        <span v-if="questions.length >= requiredQuestionNumber">
                            {{ questions.length }} questions
                        </span>
                        <span v-else>
                            There are  {{ questions.length }} questions instead of {{ requiredQuestionNumber }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="test-exam-body col-9">
                <div class="test-exam-questions">
                    <div class="test-exam-question" v-for="(q, i) in questions" v-bind:key="i">
                        <question-component v-model="questions[i]" :index="i + 1" />
                    </div>
                    <!--<div :class="['test-exam-question', q['isCorrect'] === null ? '' : (q.isCorrect ?  'correct' : 'false')]"
                         v-for="(q, i) in questions" v-bind:key="i" :ref="'question' + i">
                        <div> {{ i + 1 }}#
                            {{ q.question }}
                        </div>
                        <b-form-radio-group v-model="questions[i].selected">
                            <b-form-radio v-for="(a, ai) in q.answers" v-bind:key="i*100 + ai" :value="ai">
                                {{ a.answer }} {{ a.correct ? '( Correct )' : '' }}
                            </b-form-radio>
                        </b-form-radio-group>
                    </div>-->
                </div>
                <div class="test-exam-buttons" v-if="isExamGoing">
                    <b-btn variant="outline-primary" @click="passExam(false)">
                        Pass
                    </b-btn>
                </div>
            </div>
            <div class="test-exam-toolbar col-3">
                <div class="test-exam-score">
                    Minimal score: {{ passScore }}%
                </div>
                <div class="test-exam-countdown">
                    <countdown-component :minutes="examTime" :showHours="false"
                                         v-model="isExamGoing" label="Time before auto-complete"
                                         @end="passExam(true)">
                        <em>
                            Time before auto-complete
                        </em>
                    </countdown-component>
                </div>
                <div class="test-exam-questions-references">
                    <div class="test-exam-questions-reference" v-for="(q, i) in questions" v-bind:key="'ref' + i"
                         @click="scrollToQuestion(i)">
                        {{ i + 1 }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { shuffleQuestions, convertQuestions } from '../../js/questions'
import CountdownComponent from '../Utilities/CountdownComponent.vue'
import QuestionComponent from './QuestionComponent.vue'
export default{
  components: {
    CountdownComponent,
    QuestionComponent
  },
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      questions: [],
      isTestExam: null,
      isLoaded: {
        training: false,
        questions: false
      },
      examTime: null,
      passScore: null,
      trainingName: null,
      requiredQuestionNumber: null,
      tutorialName: null,
      isExamGoing: false,
      isStarted: false
    }
  },
  mounted () {
    this.loadTraining()
    this.loadQuestions()
  },
  computed: {
    isAllLoaded () {
      return this.isLoaded.training && this.isLoaded.questions
    },
    participant () {
      return this.$store.state.trainingParticipating.find(p => p.training_id === this.id)
    }
  },
  methods: {
    loadTraining () {
      this.axios.get('/trainings/' + this.id).then(response => {
        console.log(response.data)
        this.isTestExam = response.data.is_test_exam
        this.examTime = response.data.exam_time
        this.passScore = response.data.pass_score
        this.trainingName = response.data.title
        this.tutorialName = response.data.tutorial ? response.data.tutorial.title : null
        this.requiredQuestionNumber = response.data.question_number
        this.isLoaded.training = true
      })
    },
    loadQuestions () {
      this.axios.get('/trainings/' + this.id + '/questions').then(response => {
        this.questions = shuffleQuestions(convertQuestions(response.data))

        this.isLoaded.questions = true
      })
    },
    getDifficultyClass (d) {
      return 'test-exam-difficulty-' + d
    },
    passExam (force = false) {
      if (!((force || confirm('Are you sure?')) && this.isExamGoing)) {
        return
      }
      for (let i = 0; i < this.questions.length; i++) {
        if (this.questions[i].selected !== null) {
          console.log(this.questions[i].answers[this.questions[i].selected])
          this.questions[i]['isCorrect'] = this.questions[i].answers[this.questions[i].selected].correct
        } else {
          this.questions[i]['isCorrect'] = false
        }
      }
      let totalScore = this.questions.map(q => q.difficulty).reduce((a, b) => a + b, 0)
      let myScore = this.questions.filter(q => q.isCorrect).map(q => q.difficulty).reduce((a, b) => a + b, 0)
      let score = 100 * myScore / totalScore
      let passed = score >= this.passScore
      if (passed) {
        alert('Congratulations, you passed test!')
      } else {
        alert('Sorry, but you got only ' + score + '% of ' + this.passScore + '%')
      }
      this.saveResults(score, passed)
      this.isExamGoing = false
    },
    saveResults (score, passed) {
      this.axios.post('trainings/' + this.id + '/participants', {
        score: score
      })
      if (passed) {
        this.$emit('passed', score)
      } else {
        this.$emit('fail', score)
      }
    },
    startExam () {
      this.isExamGoing = true
      this.isStarted = true
    },
    scrollToQuestion (index) {
      let element = this.$refs['question' + index]
      if (element) {
        console.log(element)
        element[0].scrollIntoView()
      }
    },
    exit () {
      if (confirm('Do you wanna exit?')) {
        this.passExam(true)
        this.$emit('close', true)
      }
    }
  }
}
</script>
<style lang="sass">
.test-exam-questions
    text-align: left

.test-exam-component
    position: relative
    min-height: 100%

.test-exam-start
    text-align: left
    margin-left: 50%
    margin-top: 10%
    border: 1px solid #20202050
    border-radius: 10px
    padding: .6rem 1rem
    transform: translateX(-50%)

.test-exam-header
    border-bottom: 1px solid #20202080

.test-exam-name
    font-size: 1.7rem

.start-training-name
    font-size: 1.7rem

.start-questions-count
    font-size: .7rem
    color: #8D8D8D

.start-info-header
    font-weight: bolder
    margin: .3rem auto

.test-exam-question
    margin-top: 1rem
    margin-bottom: 1rem

.test-exam-score
    font-style: italic

.test-exam-toolbar
    border-left: 1px solid #30303060

.test-exam-questions-references
    display: flex
    flex-wrap: wrap
    border: 1px solid #30303050

.test-exam-questions-reference
  cursor: pointer
  padding: .3rem .5rem
  margin-right: .4rem


.start-controls
    margin: .5rem auto

</style>
