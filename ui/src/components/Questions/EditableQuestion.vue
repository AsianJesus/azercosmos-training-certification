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
                    <input type="radio" :name="'correct_answer_' + question.id" v-model="form.correct_answer" :value="0">

                    </input>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer2" placeholder="Answer #2">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + question.id" v-model="form.correct_answer" :value="1">

                    </input>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer3" placeholder="Answer #3">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + question.id" v-model="form.correct_answer" :value="2">

                    </input>
                </div>
                <div class="col-10">
                    <b-form-input v-model="form.answer4" placeholder="Answer #4">

                    </b-form-input>
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + question.id" v-model="form.correct_answer" :value="3">

                    </input>
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
                    <div v-if="question.file">
                        <a :href="$store.state.serverURL + '/' + question.file.path" target="_blank ">
                            Attachment
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div>
                        <strong v-if="question.correct_answer === 0">{{ question.answer1 }}</strong>
                        <span v-else> {{question.answer1 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 1">{{ question.answer2 }}</strong>
                        <span v-else> {{question.answer2 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 2">{{ question.answer3 }}</strong>
                        <span v-else> {{question.answer3 }}</span>
                    </div>
                    <div>
                        <strong v-if="question.correct_answer === 3">{{ question.answer4 }}</strong>
                        <span v-else> {{question.answer4 }}</span>
                    </div>
                </div>
                <div class="col-2">
                    {{ author }}
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
        question: '',
        answer1: '',
        answer2: '',
        answer3: '',
        answer4: '',
        correct_answer: null
      },
      isEditing: false
    }
  },
  computed: {
    difficultyOptions: () => difficultyOptions,
    author () {
      let res = this.$store.state.users.filter(x => x.ID === this.question.author_id)
      return res.length ? res[0].NAME : 'Undefined'
    }
  },
  mounted () {
    this.form = JSON.parse(JSON.stringify(this.question))
  },
  methods: {
    startEdit () {
      this.isEditing = true
    },
    cancelEdit () {
      this.form = JSON.parse(JSON.stringify(this.question))
      this.isEditing = false
    },
    saveEdit () {
      this.$emit('saveEdit', this.form)
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
