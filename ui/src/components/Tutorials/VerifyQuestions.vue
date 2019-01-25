<template>
    <div class="verify-questions-component">
        <div class="verify-questions-close-button">
            <b-btn variant="outline-success" @click="$emit('close', true)">Close</b-btn>
        </div>
        <div class="verify-questions-list">
            <div class="verify-question-holder" v-for="(q, i) in questions" v-bind:key="i">
                <question-to-verify :question="q" @deleteQuestion="deleteQuestion(q.id)" @undoDeleting="undoDeleting(q.id)"
                                    @verify="verifyQuestion(q.id)" @unverify="unverifyQuestion(q.id)">

                </question-to-verify>
            </div>
        </div>
        <div class="verify-questions-buttons">
            <b-btn variant="outline-success" @click="save()">Save</b-btn>
        </div>
        {{ questionsToVerify }}
        {{ questionsToDelete }}
    </div>
</template>
<script>
import QuestionToVerify from '../Questions/QuestionToVerify.vue'
export default{
  components: {
    QuestionToVerify
  },
  props: {
    tutorial: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      questions: [],
      questionsToDelete: [],
      questionsToVerify: []
    }
  },
  mounted () {
    this.loadQuestions()
  },
  methods: {
    deleteQuestion (id) {
      this.questionsToDelete.push(id)
    },
    undoDeleting (id) {
      this.questionsToDelete = this.questionsToDelete.filter(x => x === id)
    },
    verifyQuestion (id) {
      this.questionsToVerify.push(id)
      for (let i = 0; i < this.questions.length; i++) {
        if (this.questions[i].id === id) {
          let q = this.questions[i]
          q.verified = true
          this.$set(this.questions, i, q)
        }
      }
    },
    unverifyQuestion (id) {
      this.questionsToVerify = this.questionsToVerify.filter(x => x !== id)
      for (let i = 0; i < this.questions.length; i++) {
        if (this.questions[i].id === id) {
          let q = this.questions[i]
          q.verified = false
          this.$set(this.questions, i, q)
        }
      }
    },
    loadQuestions () {
      this.axios.get('/tutorials/' + this.tutorial.id + '/questions').then(response => {
        this.questions = response.data.unverified
      })
    },
    save () {
      this.questionsToVerify = this.questionsToVerify.filter(q => !this.questionsToDelete.some(d => d === q))
      let toVerify = {}
      this.questionsToVerify.forEach(x => {
        toVerify[x] = {verified: true}
      })
      let props = {
        questions_count: this.tutorial.questions_count,
        verified_questions_count: this.tutorial.verified_questions_count
      }
      this.axios.all([
        this.axios.delete('/questions', {
          params: {
            ids: this.questionsToDelete
          }
        }),
        this.axios.patch('/questions', toVerify)
      ]).then(response => {
        console.log(response)
        props.questions_count -= response[0].data
        props.verified_questions_count += response[1].data
        this.$store.commit('updateTutorial', {
          id: this.tutorial.id,
          props
        })
      })
      this.$emit('close', true)
    }
  }
}
</script>
<style>

</style>
