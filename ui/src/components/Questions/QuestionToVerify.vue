<template>
    <div class="question-to-verify-component">
        <div class="row-margi question-to-verify-buttons" v-if="isDeleted">
            <div class="col-2 offset-10">
                <b-btn variant="outline-primary" @click="undoDeleting">
                    Undo
                </b-btn>
            </div>
        </div>
        <div v-else>
            <h5>
                Question #{{ question.id }}:
                {{ question.question }}
            </h5>
            <div class="row-margin">
                <div class="col-10">
                    <div :class="question.correct_answer === 0 ? 'question-to-verify-correct' : ''">
                        Answer #1: {{ question.answer1 }}
                    </div>
                    <div :class="question.correct_answer === 1 ? 'question-to-verify-correct' : ''">
                        Answer #2: {{ question.answer2 }}
                    </div>
                    <div :class="question.correct_answer === 2 ? 'question-to-verify-correct' : ''">
                        Answer #3: {{ question.answer3 }}
                    </div>
                    <div :class="question.correct_answer === 3 ? 'question-to-verify-correct' : ''">
                        Answer #4: {{ question.answer4 }}
                    </div>
                    <a :href="$store.state.serverURL + '/' + question.file.path" target="_blank" v-if="question.file">
                        Attachment
                    </a>
                </div>
                <div class="col-2 question-to-verify-buttons">
                    <b-btn variant="outline-success verify-button" @click="verify" v-if="!question.verified">
                        <v-icon name="check" />
                    </b-btn>
                    <b-btn variant="outline-warning verify-button" @click="unverify" v-else>
                        <v-icon name="ban" />
                    </b-btn>
                    <b-btn variant="outline-danger" class="delete-button" @click="deleteQuestion">
                        <v-icon name="trash" />
                    </b-btn>
                </div>
            </div>
        </div>
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
.question-to-verify-component{
    text-align: left;
}
.question-to-verify-correct{
    font-weight: bolder;
}
.question-to-verify-buttons{
    text-align: right;
}
</style>
