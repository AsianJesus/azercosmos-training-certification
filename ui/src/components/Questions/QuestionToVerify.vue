<template>
    <div class="question-to-verify-component row">
        <div v-if="isDeleted">
            <b-btn variant="outline-primary" @click="undoDeleting">
                Undo deleting
            </b-btn>
        </div>
        <table class="table" v-else>
            <tr>
                <td>
                    <div>
                        {{ question.question }}
                    </div>
                    <div v-if="question.file">
                        <a :href="$store.state.serverURL + '/' + question.file.path" target="_blank">
                            Attachment
                        </a>
                    </div>
                </td>
                <td>
                    <div>
                        <strong v-if="question.correct_answer === 0"> {{ question.answer1 }}</strong>
                        <span v-else> {{ question.answer1 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 1"> {{ question.answer2 }}</strong>
                        <span v-else> {{ question.answer2 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 2"> {{ question.answer3 }}</strong>
                        <span v-else> {{ question.answer3 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 3"> {{ question.answer4 }}</strong>
                        <span v-else> {{ question.answer4 }}</span>
                    </div>
                </td>
                <td>
                    <b-btn variant="outline-success verify-button" @click="verify" v-if="!question.verified">
                        <v-icon name="check"></v-icon>
                    </b-btn>
                    <b-btn variant="outline-warning verify-button" @click="unverify" v-else>
                        <v-icon name="ban" />
                    </b-btn>
                    <b-btn variant="outline-danger" class="delete-button" @click="deleteQuestion">
                        <v-icon name="trash" />
                    </b-btn>
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
<style lang="css">

</style>
