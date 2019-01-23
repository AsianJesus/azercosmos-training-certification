<template>
    <div class="editable-question-component">
        <div class="editable-question-deleted" v-if="isDeleted">
            Deleted
            <b-btn @click="$emit('undoDelete')" variant="outline-primary">Undo</b-btn>
        </div>
        <div class="editable-question-edit" v-else-if="isEditing">
            <div class="row">
                <div class="col-12">
                    <b-form-input v-model="form.question" placeholder="Question">

                    </b-form-input>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer1" placeholder="Answer #1">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <b-form-radio type="radiobutton" v-model="form.correct_answer" :value="0">

                    </b-form-radio>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer2" placeholder="Answer #2">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <b-form-radio type="radiobutton" v-model="form.correct_answer" :value="1">

                    </b-form-radio>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer3" placeholder="Answer #3">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <b-form-radio type="radiobutton" v-model="form.correct_answer" :value="2">

                    </b-form-radio>
                </div>
            </div>
            <div class="editable-question-edit-buttons">
                <b-btn @click="saveEdit" variant="outline-success">Save</b-btn>
                <b-btn @click="cancelEdit" variant="outline-warning">Cancel</b-btn>
                <b-btn @click="deleteQuestion" variant="outline-warning">Delete</b-btn>
            </div>
        </div>
        <div class="editable-question-read" v-else>
            <div class="row">
                <div class="col-3">
                    <h6>
                        {{ question.question }}
                    </h6>
                </div>
                <div class="col">
                    <div>
                        <em v-if="question.correct_answer === 0">{{ question.answer1 }}</em>
                        <span v-else> {{question.answer1 }}</span>
                    </div>
                    <div>
                        <em v-if="question.correct_answer === 1">{{ question.answer2 }}</em>
                        <span v-else> {{question.answer2 }}</span>
                    </div>
                    <div>
                        <em v-if="question.correct_answer === 2">{{ question.answer3 }}</em>
                        <span v-else> {{question.answer3 }}</span>
                    </div>
                    <div>
                        <em v-if="question.correct_answer === 3">{{ question.answer4 }}</em>
                        <span v-else> {{question.answer4 }}</span>
                    </div>
                </div>
                <div class="col-2">
                    {{ question.author_id }}
                </div>
            </div>
            <div class="editable-question-read-buttons">
                <b-btn @click="startEdit" variant="outline-secondary">Edit</b-btn>
                <b-btn @click="deleteQuestion" variant="outline-danger">Delete</b-btn>
            </div>
        </div>
    </div>
</template>
<script>
import { difficultyOptions } from '../../js/difficulties'
export default{
  props: {
    question: {
      type: Object,
      required: true
    },
    isDeleted: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      form: {

      },
      isEditing: false
    }
  },
  computed: {
    difficultyOptions: () => difficultyOptions
  },
  mounted () {

  },
  methods: {
    startEdit () {
      this.isEditing = true
    },
    cancelEdit () {
      this.isEditing = false
      this.form = JSON.parse(JSON.stringify(this.question))
    },
    saveEdit () {
      this.$emit('save', this.form)
      this.isEditing = false
    },
    deleteQuestion () {
      this.$emit('deleteQuestion')
    }
  }
}
</script>
<style>

</style>
