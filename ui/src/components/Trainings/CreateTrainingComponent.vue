<template>
    <div class="create-training-component">
        <div class="create-training-headline">
            <b-form-input type="text" v-model="title" placeholder="Title" :state="showError && errors.title ? false : null">

            </b-form-input>
        </div>
        <div class="create-training-content">
            <div class="create-training-observers">
                <multi-select :options="usersOptions" :selected-options="observers"
                                @select="addObserver" placeholder="Observers"></multi-select>
            </div>
            <div class="create-training-description">
                <b-form-textarea v-model="description" placeholder="Description" :state="showError && errors.description ? false : null" rows="3">
                </b-form-textarea>
                <b-form-textarea v-model="reference" placeholder="References"></b-form-textarea>
            </div>
            <div class="create-training-attachment">
                <b-form-file v-model="attachment" placeholder="Your file..."></b-form-file>
            </div>
            <div class="create-training-test">
                <div class="create-training-test-button">
                    <b-form-checkbox v-model="isTestExam">Is training test?</b-form-checkbox>
                </div>
                <div class="create-training-test-body" v-if="isTestExam">
                    <div>
                        <model-select :options="tutorialsOptions" v-model="tutorialID"
                                      :isError="showError && errors.tutorialID">
                        </model-select>
                    </div>
                    <div>
                        <b-form-input type="number" v-model="passScore" placeholder="Minimal score (%)"
                                      :state="showError && errors.passScore ? false : null">
                        </b-form-input>
                    </div>
                    <div>
                        <b-form-input type="number" v-model="examTime" placeholder="Time of exam (in minutes)"
                                      :state="showError && errors.examTime ? false : null">
                        </b-form-input>
                    </div>
                </div>
            </div>
            <div class="create-training-participants">
                <new-participant-component v-model="participants" @error="participantErrors = $event" :showError="showError">
                </new-participant-component>
            </div>
        </div>
        <div class="create-training-buttons">
            <b-btn variant="outline-success" @click="create()">Create</b-btn>
        </div>
    </div>
</template>
<script>
import { MultiSelect, ModelSelect } from 'vue-search-select'
import NewParticipantComponent from './NewParticipantsComponent.vue'
export default{
  components: {
    MultiSelect,
    ModelSelect,
    NewParticipantComponent
  },
  data () {
    return {
      participants: [],
      observers: [],
      title: '',
      description: '',
      reference: '',
      attachment: null,
      isTestExam: false,
      tutorialID: null,
      passScore: null,
      examTime: null,
      participantErrors: false,
      showError: false
    }
  },
  computed: {
    usersOptions () {
      return this.$store.getters.usersOptions.filter(x => x.value !== this.$store.state.userID)
    },
    tutorialsOptions () {
      let result = this.$store.state.tutorials.my.concat(this.$store.state.tutorials.observing)
      result = result.concat(this.$store.state.tutorials.moderating).filter((x, i, self) => {
        return self.findIndex(a => a.id === x.id) === i
      }).map(x => {
        return {
          text: x.title,
          value: x.id
        }
      })
      result.push({
        text: 'Select tutorial',
        value: null
      })
      return result
    },
    errors () {
      return {
        title: !this.title,
        description: !this.description,
        participants: this.participantErrors,
        tutorialID: this.isTestExam && !this.tutorialID,
        passScore: this.isTestExam && !this.passScore,
        examTime: this.isTestExam && (!this.examTime || this.examTime < 0)
      }
    }
  },
  methods: {
    addObserver (observer) {
      this.observers = observer
    },
    create () {
      if (Object.values(this.errors).some(x => x)) {
        this.showError = true
        return
      }
      this.showError = false
      let form = new FormData()
      form.set('title', this.title)
      form.set('description', this.description)
      form.set('reference', this.reference)
      form.set('is_test_exam', this.isTestExam)
      form.set('originator_id', this.$store.state.userID)
      if (this.isTestExam) {
        form.set('tutorial_id', this.tutorialID)
        form.set('pass_score', this.passScore)
        form.set('exam_time', this.examTime)
      }
      if (this.attachment) {
        form.append('file', this.attachment)
      }
      this.observers.forEach(o => {
        form.set('observers[]', o.value)
      })
      this.participants.forEach((p, i) => {
        form.set('participants[' + i + '][participant_id]', p.participant_id)
        form.set('participants[' + i + '][start_date]', p.start_date)
        form.set('participants[' + i + '][end_date]', p.end_date)
      })
      this.axios.post('/trainings', form).then(response => {
        console.log(response)
        this.$store.commit('addTraining', {
            type: 'observing',
            training: response.data
        })
      })
    }
  }
}
</script>
<style>

</style>
