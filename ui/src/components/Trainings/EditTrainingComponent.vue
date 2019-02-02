<template>
    <div class="edit-training-component">
        <div class="edit-training-headline edit-training-info-element">
            <span class="edit-training-training-id">
                Training {{ '#' + training.id }}
            </span>
            <span class="edit-training-training-status">
                Status: {{ getTrainingStatus(training) }}
            </span>
        </div>
        <div class="edit-training-body">
            <div class="row-margin edit-training-info-element">
                <div class="col-6">Trainer</div>
                <div class="col-4 offset-2">Training record date</div>
                <div class="col-6"><b-form-input :value="originator ? originator.NAME : ''" readonly /></div>
                <div class="col-4 offset-2"><b-form-input v-model="created_at" readonly /></div>
            </div>
            <div class="edit-training-title edit-training-info-element">
                Title
                <b-form-input v-model="title" :state="showError && errors.title ? false : null" />
            </div>
            <div class="edit-training-description edit-training-info-element">
                Description
                <b-form-input v-model="description" :state="showError && errors.description ? false : null" />
            </div>
            <div class="edit-training-info-element">
                <div class="row-margin">
                    <div class="edit-training-reference col">
                        Reference
                    </div>
                    <div class="edit-training-attachment col-3">
                        Attachment
                    </div>
                </div>
                <div class="row-margin">
                    <div class="edit-training-reference col">
                        <b-form-input v-model="reference" />
                    </div>
                    <div class="col-3">
                        <a :href="$store.state.serverURL + '/' + existingFile" target="_blank" v-if="existingFile">
                            Open
                        </a>
                        <span v-else>
                            No attachment
                        </span>
                        <b-form-file v-model="attachment" placeholder="Attach" />
                    </div>
                </div>
            </div>
            <div class="edit-training-test-button">
                <b-form-checkbox v-model="isTestExam" :value="1" :unchecked-value="0">Is training test?</b-form-checkbox>
            </div>
            <div class="edit-training-test-info edit-training-info-element" v-if="isTestExam">
                <div class="row-margin">
                    <div class="edit-training-tutorial col-7">
                        Tutorial
                    </div>
                    <div class="edit-training-questions-number col-2">
                        Questions number
                    </div>
                    <div class="edit-training-pass-score col-2">Success threshold</div>
                    <div class="edit-training-time col-1">Time</div>
                </div>
                <div class="row-margin">
                    <div class="edit-training-tutorial col-7">
                        <model-select :options="tutorialsOptions" v-model="tutorialID"
                                      :isError="showError && errors.tutorialID">
                        </model-select>
                    </div>
                    <div class="edit-training-questions-number col-2">
                        <b-form-input v-model="questionsCount" :state="showError && errors.questionsCount ? false : null" />
                    </div>
                    <div class="edit-training-pass-score col-2">
                        <b-form-input v-model="passScore" :state="showError && errors.passScore ? false : null" />
                    </div>
                    <div class="edit-training-time col-1">
                        <b-form-input v-model="examTime" :state="showError && errors.examtime ? false : null" />
                    </div>
                </div>
            </div>
            <div class="edit-training-participants" v-if="participants">
                <h5>
                    Participants
                </h5>
                <new-participant-component :participants-initial="participantsInitial" @input ="participants = $event"
                                           @error="participantErrors = $event" :showError="showError">
                </new-participant-component>
            </div>
            <h5>
                Observers
            </h5>
            <div class="edit-training-observers">
                <multi-select :options="usersOptions" :selected-options="observers"
                              @select="addObserver" placeholder="Observers"></multi-select>
            </div>
            <div class="edit-training-buttons">
                <b-btn variant="outline-success" @click="save()">Save</b-btn>
            </div>
        </div>
        <!--<div class="edit-training-headline">
            <b-form-input type="text" v-model="title" placeholder="Title" :state="showError && errors.title ? false : null">

            </b-form-input>
        </div>
        <div class="edit-training-content">
            <div class="edit-training-observers">
                <multi-select :options="usersOptions" :selected-options="observers"
                              @select="addObserver" placeholder="Observers"></multi-select>
            </div>
            <div class="edit-training-description">
                <b-form-input v-model="description" placeholder="Description" :state="showError && errors.description ? false : null" rows="3">
                </b-form-input>
                <b-form-input v-model="reference" class="edit-training-reference" placeholder="References"></b-form-input>
            </div>
            <div class="edit-training-attachment">
                <a :href="$store.state.serverURL + '/' + existingFile" v-if="existingFile" target="_blank">
                    Attachment
                </a>
                <b-form-file v-model="attachment" placeholder="New attachment..."></b-form-file>
            </div>
            <div class="edit-training-test">
                <div class="edit-training-test-button">
                    <b-form-checkbox v-model="isTestExam" :value="1" :unchecked-value="0">Is training test?</b-form-checkbox>
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
        </div>-->
    </div>
</template>
<script>
import { MultiSelect, ModelSelect } from 'vue-search-select'
import NewParticipantComponent from './NewParticipantsComponent.vue'
import { getStatus, getTrainingStatus } from '../../js/statuses'
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
      questionsCount: null,
      originator: null,
      created_at: null
    }
  },
  mounted () {
    this.title = this.training.title
    this.description = this.training.description
    this.reference = this.training.reference
    this.created_at = this.training.created_at
    this.existingFile = this.training.file ? this.training.file.path : null
    this.originator = this.training.originator
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
    getTrainingStatus: getTrainingStatus,
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
      form.set('is_test_exam', this.isTestExam ? 1 : 0)
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
        this.$store.commit('updateParticipatingTraining', response.data)
        this.$emit('close', true)
      })
    }
  }
}
</script>
<style>
    .edit-training-headline{
        border-bottom: 1px solid #20202020;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
    .edit-training-training-id, .edit-training-training-status{
        border: 1px solid #10101040;
        padding: .2rem .4rem;
        margin-left: 2rem;
        margin-right: 2rem;
        border-radius: 5px;
        box-shadow: 0 1px #20202020;
    }
    .edit-training-info-element{
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    .edit-training-body{
        text-align: left;
    }
    .edit-training-buttons{
        text-align: right;
    }
</style>
