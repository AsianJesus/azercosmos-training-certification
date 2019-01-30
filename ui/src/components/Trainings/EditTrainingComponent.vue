<template>
    <div class="edit-training-component">
        <div class="edit-training-headline">
            <b-form-input type="text" v-model="title" placeholder="Title" :state="showError && errors.title ? false : null">

            </b-form-input>
        </div>
        <div class="edit-training-content">
            <div class="edit-training-observers">
                <multi-select :options="usersOptions" :selected-options="observers"
                              @select="addObserver" placeholder="Observers"></multi-select>
            </div>
            <div class="edit-training-description">
                <b-form-textarea v-model="description" placeholder="Description" :state="showError && errors.description ? false : null" rows="3">
                </b-form-textarea>
                <b-form-textarea v-model="reference" placeholder="References"></b-form-textarea>
            </div>
            <div class="edit-training-attachment">
                <a :href="$store.state.serverURL + '/' + existingFile" v-if="existingFile" target="_blank">
                    Attachment
                </a>
                <b-form-file v-model="attachment" placeholder="New attachment..."></b-form-file>
            </div>
            <div class="edit-training-test">
                <div class="edit-training-test-button">
                    <b-form-checkbox v-model="isTestExam">Is training test?</b-form-checkbox>
                </div>
                <div class="edit-training-test-body" v-if="isTestExam">
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
                        <b-form-input type="number" v-model="questionsCount" placeholder="Questions count"
                                      :state="showError && errors.questionsCount ? false : null">
                        </b-form-input>
                    </div>
                    <div>
                        <b-form-input type="number" v-model="examTime" placeholder="Time of exam (in minutes)"
                                      :state="showError && errors.examTime ? false : null">
                        </b-form-input>
                    </div>
                </div>
            </div>
            <div class="edit-training-participants">
                <new-participant-component :participants-initial="participantsInitial" @input ="participants = $event"
                                           @error="participantErrors = $event" :showError="showError">
                </new-participant-component>
            </div>
        </div>
        <div class="edit-training-buttons">
            <b-btn variant="outline-success" @click="save()">Save</b-btn>
        </div>
    </div>
</template>
<script>
import { MultiSelect, ModelSelect } from 'vue-search-select'
import NewParticipantComponent from './NewParticipantsComponent.vue'
export default{
  props: {
    training: {
      type: Object,
      required: true
    }
  },
  components: {
    MultiSelect,
    ModelSelect,
    NewParticipantComponent
  },
  data () {
    return {
      title: '',
      description: '',
      reference: '',
      participants: [],
      participantsInitial: [],
      observers: [],
      tutorialID: null,
      isTestExam: false,
      passScore: null,
      examTime: null,
      participantErrors: false,
      showError: false,
      attachment: null,
      existingFile: null,
      questionsCount: null
    }
  },
  mounted () {
    this.title = this.training.title
    this.description = this.training.description
    this.reference = this.training.reference
    this.existingFile = this.training.file ? this.training.file.path : null
    this.participantsInitial = this.training.participants.map(p => {
      return {
        participant_id: p.participant_id,
        start_date: p.start_date,
        end_date: p.end_date
      }
    })
    this.participants = []
    this.observers = this.training.observers.map(o => {
      return {
        text: o.user.NAME,
        value: o.user.ID
      }
    })
    this.isTestExam = this.training.is_test_exam
    this.passScore = this.training.pass_score
    this.questionsCount = this.training.question_number
    this.tutorialID = this.training.tutorial_id
    this.examTime = this.training.exam_time
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
        passScore: this.isTestExam && (!this.passScore || this.passScore > 100),
        examTime: this.isTestExam && (!this.examTime || this.examTime < 0),
        questionsCount: this.isTestExam && (!this.questionsCount || this.questionsCount < 0)
      }
    }
  },
  methods: {
    addObserver (observers) {
      this.observers = observers
    },
    updateParticipantsInfo () {
      let participantsToUpdate = this.training.participants.filter(p => this.participants.some(par => par.participant_id === p.participant_id))
      let infoToSend = {}
      participantsToUpdate.forEach(p => {
        infoToSend[p.id] = {
          start_date: this.participants.find(x => x.participant_id === p.participant_id).start_date,
          end_date: this.participants.find(x => x.participant_id === p.participant_id).end_date
        }
      })
      return this.axios.patch('/participants', infoToSend)
    },
    save: async function () {
      if (Object.values(this.errors).some(x => x)) {
        this.showError = true
        return
      }
      this.showError = false
      await this.updateParticipantsInfo()
      let form = new FormData()
      form.set('title', this.title)
      form.set('description', this.description)
      form.set('reference', this.reference)
      form.set('is_test_exam', this.isTestExam)
      form.set('pass_score', this.isTestExam ? this.passScore : null)
      form.set('question_number', this.isTestExam ? this.questionsCount : null)
      form.set('tutorial_id', this.isTestExam ? this.tutorialID : null)
      form.set('exam_time', this.isTestExam ? this.examTime : null)
      let observersToDelete = this.training.observers.filter(o => !this.observers.some(ob => ob.value === o.observer_id))
      observersToDelete.forEach(o => {
        form.append('o_to_delete[]', o.observer_id)
      })
      let observersToAdd = this.observers.filter(ob => !this.training.observers.some(o => o.observer_id === ob.value))
      observersToAdd.forEach(ob => {
        form.append('o_to_add[]', ob.value)
      })
      let participantsToDelete = this.training.participants.filter(p => !this.participants.some(par => par.participant_id === p.participant_id))
      participantsToDelete.forEach(p => {
        form.append('p_to_delete[]', p.participant_id)
      })
      let participantsToAdd = this.participants.filter(par => !this.training.participants.some(p => p.participant_id === par.participant_id))
      participantsToAdd.forEach(par => {
        form.append('p_to_add[]', par.participant_id)
      })
      if (this.attachment) {
        form.append('file', this.attachment)
      }
      this.axios.post('/trainings/' + this.training.id, form).then(response => {
        this.$store.commit('updateObservedTraining', response.data)
        this.$emit('close', true)
      })
    }
  }
}
</script>
<style>

</style>
