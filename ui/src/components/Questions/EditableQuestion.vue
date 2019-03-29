<template>
    <div class="editable-question-component">
        <div v-if="question.id">
            Question #{{ question.id }}
        </div>
        <div class="editable-question-deleted row-margin" v-if="isDeleted">
            <div class="col-2 offset-10" style="text-align: right;">
                <b-btn @click="$emit('undoDelete')" variant="outline-primary">Undo</b-btn>
            </div>
        </div>
        <div class="editable-question-edit" v-else-if="isEditing">
            <div class="row-margin editable-question-edit-row">
                <div class="col-12" v-if="question.id">
                    <b-form-textarea v-model="form.question" placeholder="Question" :rows="2"
                                     :state="showError && errors.question ? false : null" />
                </div>
                <div class="col-6" style="margin-top: .3rem;">
                    <b-form-select v-model="form.difficulty" :options="difficultyOptions"
                                   :state="showError && errors.difficulty ? false : null"/>
                </div>
            </div>
            <div class="row-margin editable-question-edit-row">
                <div class="col-10">
                    <b-form-textarea v-model="form.answer1" placeholder="Answer #1" :state="showError && errors.answer1 ? false : null" />
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + id" v-model="form.correct_answer" :value="0">
                </div>
            </div>
            <div class="row-margin editable-question-edit-row">
                <div class="col-10">
                    <b-form-textarea v-model="form.answer2" placeholder="Answer #2" :state="showError && errors.answer2 ? false : null" />
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + id" v-model="form.correct_answer" :value="1">
                </div>
            </div>
            <div class="row-margin editable-question-edit-row">
                <div class="col-10">
                    <b-form-textarea v-model="form.answer3" placeholder="Answer #3" :state="showError && errors.answer3 ? false : null" />
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + id" v-model="form.correct_answer" :value="2">
                </div>
            </div>
            <div class="row-margin editable-question-edit-row">
                <div class="col-10">
                    <b-form-textarea v-model="form.answer4" placeholder="Answer #4" :state="showError && errors.answer4 ? false : null" />
                </div>
                <div class="col-2">
                    <input type="radio" :name="'correct_answer_' + id" v-model="form.correct_answer" :value="3">
                </div>
            </div>
            <div class="editable-question-edit-buttons" style="text-align: right;">
                <b-btn @click="saveEdit" variant="outline-success" class="edit-button">
                    <v-icon name="pen" />
                </b-btn>
                <b-btn @click="cancelEdit" variant="outline-warning" class="delete-button">
                    <v-icon name="ban" />
                </b-btn>
                <b-btn @click="deleteQuestion" variant="outline-danger" class="delete-button">
                    <v-icon name="trash" />
                </b-btn>
            </div>
        </div>
        <div class="editable-question-read" v-else>
            <div class="row-margin editable-question-read-row">
                <div class="col-3">
                    <h6>
                        {{ question.question }}
                    </h6>
                    <span>
                        {{ getDifficultyName(question.difficulty) }}
                    </span>
                    <div v-if="question.file && showFile">
                        <a :href="$store.state.serverURL + '/' + question.file.path" target="_blank ">
                            Attachment
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div :class="question.correct_answer === 0 ? 'editable-question-correct' : ''">
                        Answer #1: {{ question.answer1 }}
                    </div>
                    <div :class="question.correct_answer === 1 ? 'editable-question-correct' : ''">
                        Answer #2: {{ question.answer2 }}
                    </div>
                    <div :class="question.correct_answer === 2 ? 'editable-question-correct' : ''">
                        Answer #3: {{ question.answer3 }}
                    </div>
                    <div :class="question.correct_answer === 3 ? 'editable-question-correct' : ''">
                        Answer #4: {{ question.answer4 }}
                    </div>
                </div>
                <div class="col-2" v-if="showAuthor">
                    {{ author }}
                </div>
                <div class="col" style="text-align: right;">
                    <b-btn @click="startEdit" variant="outline-secondary" class="edit-button">
                        <v-icon name="pen" />
                    </b-btn>
                    <b-btn @click="deleteQuestion" variant="outline-danger" class="delete-button">
                        <v-icon name="trash" />
                    </b-btn>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { difficultyOptions, mapDifficulty } from '../../js/difficulties'
export default{
  props: {
    question: {
      type: Object,
      required: true
    },
    isDeleted: {
      type: Boolean,
      default: false

    },
    showAuthor: {
      type: Boolean,
      default: true
    },
    showFile: {
      type: Boolean,
      default: true
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
        correct_answer: null,
        difficulty: null
      },
      id: null,
      showError: false,
      isEditing: false
    }
  },
  computed: {
    difficultyOptions: () => difficultyOptions,
    author () {
      let res = this.$store.state.users.filter(x => x.ID === this.question.author_id)
      return res.length ? res[0].NAME : 'Undefined'
    },
    errors () {
      return {
        question: !this.form.question,
        answer1: !this.form.answer1,
        answer2: !this.form.answer2,
        answer3: !this.form.answer3,
        answer4: !this.form.answer4,
        difficulty: this.form.difficulty === null
      }
    }
  },
  mounted () {
    this.form = JSON.parse(JSON.stringify(this.question))
    this.id = this.question.id || Math.random()
  },
  methods: {
    getDifficultyName: mapDifficulty,
    startEdit () {
      this.isEditing = true
    },
    cancelEdit () {
      this.form = JSON.parse(JSON.stringify(this.question))
      this.showError = false
      this.isEditing = false
    },
    saveEdit () {
      if (Object.values(this.errors).some(e => e)) {
        this.showError = true
        return
      }
      this.showError = false
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
.editable-question-edit-row{
    margin-top: .3rem;
    margin-bottom: .3rem;
}
.editable-question-correct{
    font-weight: bolder;
}
</style>
