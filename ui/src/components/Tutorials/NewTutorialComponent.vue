<template>
    <div class="new-tutorial-component">
        <h5 class="new-tutorial-headline">
            New tutorial
        </h5>
        <div class="new-tutorial-row row-margin">
            <div class="col-6">
                Title
                <b-form-input v-model="form.title" :state="showError && tutorialErrors.title ? false : null" />
            </div>
            <div class="col-6">
                Systems
                <b-form-select :options="systems" placeholder="System" v-model="form.system"
                               :state="showError && tutorialErrors.system ? false : null" />
            </div>
        </div>
        <div class="new-tutorial-row row-margin">
            <div class="col-6">
                Observers
            </div>
            <div class="col-6">
                Moderators
            </div>
            <div class="col-6">
                <multi-select :options="usersOptions" :selected-options="observers" @select="observers = $event"
                              placeholder="Observers">
                </multi-select>
            </div>
            <div class="col-6">
                <multi-select :options="usersOptions" :selected-options="moderators" @select="moderators = $event"
                              placeholder="Moderators">
                </multi-select>
            </div>
        </div>
        <div class="new-tutorial-row">
            <div>
                <table class="table table-hover">
                    <tr>
                        <th>Question</th>
                        <th>Answers</th>
                        <th>Correct</th>
                        <th>Difficulty</th>
                    </tr>
                    <tr v-for="(question, index) in form.questions" v-bind:key="index">
                        <td>{{ question.question }}</td>
                        <td>
                            <div>
                                <span>1) {{ question.answer1 }}</span>
                            </div>
                            <div>
                                <span>2) {{ question.answer2 }}</span>
                            </div>
                            <div>
                                <span>3) {{ question.answer3 }}</span>
                            </div>
                            <div>
                                <span>4) {{ question.answer4 }}</span>
                            </div>
                        </td>
                        <td>{{ question.correct_answer + 1}}</td>
                        <td>{{ mapDifficulty(question.difficulty) }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <new-question-component @addQuestion="addQuestion($event)"></new-question-component>
            </div>
        </div>
        <div class="new-tutorial-row">
            <div class="new-tutorial-component-buttons">
                <b-btn variant="outline-success" @click="createTutorial">
                    Create tutorial
                </b-btn>
                <b-btn variant="outline-warning" @click="$emit('close', true)">Close</b-btn>
            </div>
        </div>
    </div>
</template>
<script>
import { MultiSelect } from 'vue-search-select'
import { mapDifficulty, difficultyOptions } from '../../js/difficulties'
import { systemOptions } from '../../js/systems'
import NewQuestionComponent from '../Questions/NewQuestionComponent.vue'
export default{
  components: {
    MultiSelect,
    NewQuestionComponent
  },
  data () {
    return {
      form: {
        title: null,
        system: null,
        questions: []
      },
      moderators: [],
      observers: [],
      showError: false
    }
  },
  computed: {
    canCreateTutorial () {
      for (let key in this.tutorialErrors) {
        if (this.tutorialErrors[key]) {
          return false
        }
      }
      return true
    },
    tutorialErrors () {
      return {
        title: !this.form.title,
        system: !this.form.system,
        question: !this.form.questions.length
      }
    },
    difficultiesOptions () {
      return difficultyOptions
    },
    userID () {
      return 1
    },
    usersOptions () {
      return this.$store.getters.usersOptions
    },
    systems: () => systemOptions
  },
  methods: {
    addQuestion (question) {
      question.author_id = this.userID
      question.verified = true
      this.form.questions.push(question)
    },
    mapDifficulty: mapDifficulty,
    createTutorial () {
      if (!this.canCreateTutorial) {
        alert('Can\'t create tutorial')
        this.showError = true
        return
      }
      this.showError = false
      this.form.moderators = this.moderators.map(x => {
        return {
          moderator_id: x.value
        }
      })
      this.form.observers = this.observers.map(x => {
        return {
          observer_id: x.value
        }
      })
      var form = new FormData()
      this.observers.forEach(x => form.set('observers[][observer_id]', x.value))
      this.moderators.forEach(x => form.set('moderators[][moderator_id]', x.value))
      form.set('title', this.form.title)
      form.set('difficulty', this.form.difficulty)
      form.set('system', this.form.system)
      this.form.questions.forEach((q, index) => {
        Object.keys(q).forEach((key) => {
          form.set('questions[' + index + '][' + key + ']', q[key])
        })
        form.append('file' + index, q.file)
      })
      this.axios.post('/tutorials', form).then(response => {
        alert('Tutorial was successfully created')
        this.$store.commit('addTutorial', {
          type: 'my',
          tutorial: response.data
        })
        if (this.observers && this.observers.some(x => x.value === this.$store.state.userID)) {
          this.$store.commit('addTutorial', {
            type: 'observing',
            tutorial: response.data
          })
        }
        if (this.moderating && this.moderating.some(x => x.value === this.$store.state.userID)) {
          this.$store.commit('addTutorial', {
            type: 'moderating',
            tutorial: response.data
          })
        }
        this.$emit('close', true)
      }).catch(err => {
        alert('Some error occurred')
        console.log(err)
      })
    }
  }
}
</script>
<style>
.new-tutorial-headline{
    border-bottom: 1px solid #30303030;
    padding-top: .3rem;
    padding-bottom: .4rem;
    font-weight: bold;
}
.new-tutorial-component-buttons{
    text-align: right;
}
.new-tutorial-component{
    padding: .4rem 0;
    text-align: left;
}
</style>
