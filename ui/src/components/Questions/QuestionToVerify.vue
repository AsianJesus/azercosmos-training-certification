<template>
    <div class="question-to-verify-component row">
        <div v-if="isDeleted">
            <b-btn variant="outline-primary" @click="undoDeleting">
                Undo deleting
            </b-btn>
        </div>
        <table class="table" v-else>
            <tr>
                <td>{{ question.question }}</td>
                <td>
                    <div>
                        <strong v-if="question.correct_answer === 0"> {{ question.answer1 }}</strong>
                        <span v-else> {{question.answer1 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 1"> {{ question.answer2 }}</strong>
                        <span v-else> {{question.answer2 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 2"> {{ question.answer3 }}</strong>
                        <span v-else> {{question.answer3 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 3"> {{ question.answer4 }}</strong>
                        <span v-else> {{question.answer4 }}</span>
                    </div>
                </td>
                <td>
                    <div v-if="!question.verified">
                        <b-btn variant="outline-success" @click="verify">Verify</b-btn>
                    </div>
                    <div v-else>
                        <b-btn variant="outline-warning" @click="unverify">Unverify</b-btn>
                    </div>
                    <div>
                        <b-btn variant="outline-danger" @click="deleteQuestion">Delete</b-btn>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>

export default{
  props: {
    question: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      isDeleted: false
    }
  },
  methods: {
    verify () {
      this.$emit('verify')
    },
    unverify () {
      this.$emit('unverify')
    },
    deleteQuestion () {
      this.$emit('deleteQuestion')
      this.isDeleted = true
    },
    undoDeleting () {
      this.$emit('undoDeleting')
      this.isDeleted = false
    }
  }
}
</script>
<style>

</style>
