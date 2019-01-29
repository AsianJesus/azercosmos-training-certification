<template>
    <div class="test-exam-component">
        <div class="test-exam-not-loaded" v-if="!isAllLoaded">
            <div v-if="isTestExam === null">
                <h4>
                    Trainings are not loaded yet
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
            Start whenever you're ready
            <b-btn variant="outline-primary" @click="startExam">
                Start
            </b-btn>
        </div>
        <div class="test-exam-loaded row" v-else>
            <div class="test-exam-body col-9">
                <div class="test-exam-name">
                    <h5>
                        {{ trainingName }} - {{ tutorialName }}
                    </h5>
                </div>
                <div class="test-exam-not-full" v-if="questions.length < requiredQuestionNumber">
                    <h6>
                        Instead of {{ requiredQuestionNumber }} there are {{ questions.length }}
                    </h6>
                </div>
                <div class="test-exam-questions">
                    <div :class="['test-exam-question', q['isCorrect'] === null ? '' : (q.isCorrect ?  'correct' : 'false')]"
                         v-for="(q, i) in questions" v-bind:key="i" :ref="'question' + i">
                        <div>
                            {{ q.question }}
                        </div>
                        <b-form-radio-group v-model="questions[i].selected">
                            <b-form-radio v-for="(a, ai) in q.answers" v-bind:key="i*100 + ai" :value="ai">
                                {{ a.answer }} ( {{ a.correct }})
                            </b-form-radio>
                        </b-form-radio-group>
                        {{ q.selected }}
                    </div>
                </div>
                <div class="test-exam-buttons" v-if="isExamGoing">
                    <b-btn variant="outline-primary" @click="passExam(false)">
                        Pass
                    </b-btn>
                </div>
            </div>
            <div class="test-exam-toolbar col-3">
                Toolbar
                <div class="test-exam-score">
                    Minimal score: {{ passScore }}
                </div>
                <div class="test-exam-countdown">
                    <countdown-component :minutes="examTime" :showHours="false"
                                         v-model="isExamGoing" label="Time before auto-complete"
                                         @end="passExam(true)">

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
import { shuffleQuestions, shuffleAnswers, convertQuestions } from '../../js/questions'
import CountdownComponent from '../Utilities/CountdownComponent.vue'
export default{
  components: {
    CountdownComponent
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
      if (!force && !confirm('Are you sure?')) {
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
      let correctQuestionsCount = 100 * this.questions.filter(q => q.isCorrect).length / this.questions.length
      let passed = correctQuestionsCount >= this.passScore
      if (passed) {
        alert('Congratulations, you passed test!')
      } else {
        alert('Sorry, but you got only ' + correctQuestionsCount + '% of ' + this.passScore + '%')
      }
      this.isExamGoing = false
      this.saveResults(correctQuestionsCount, passed)
      this.$forceUpdate()
    },
    saveResults (score, passed) {
      this.axios.put('trainings/' + this.id + '/participants', {
        user_id: this.$store.state.userID,
        score: score
      }).then(() => {
        if (passed) {
          this.$emit('passed', score)
        }
      })
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
    }
  }
}
</script>
<style>
    .false{
        background-color: red;
        color: blue;
    }
    .correct{
        background-color: green;
        color: white;
    }
    .test-exam-questions-reference{
      border: 1px solid blue;
      cursor: pointer;
    }
</style>
