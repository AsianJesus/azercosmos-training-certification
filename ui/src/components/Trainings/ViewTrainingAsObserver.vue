<template>
    <div class="view-training-as-observer">
        <div class="vtao-headline">
            <span class="vtao-training-id">
                Training {{ '#' + training.id }}
            </span>
            <span class="vtao-training-status">
                Status: {{ getTrainingStatus(training) }}
            </span>
        </div>
        <div class="vtao-body">
            <div class="row-margin">
                <div class="col-6">Trainer</div>
                <div class="col-4 offset-2">Training record date</div>
                <div class="col-6"><b-form-input v-model="training.originator.NAME" readonly /></div>
                <div class="col-4 offset-2"><b-form-input v-model="training.created_at" readonly /></div>
            </div>
            <div class="vtao-title">
                Title
                <b-form-input v-model="training.title" readonly />
            </div>
            <div class="vtao-description">
                Description
                <b-form-input v-model="training.description" readonly />
            </div>
            <div>
                <div class="row-margin">
                    <div class="vtao-reference col">
                        Reference
                    </div>
                    <div class="vtao-attachment col-3">
                        Attachment
                    </div>
                </div>
                <div class="row-margin">
                    <div class="vtao-reference col">
                        <b-form-input v-model="training.reference" readonly />
                    </div>
                    <div class="col-3">
                        <a :href="$store.state.serverURL + '/' + training.file.path" target="_blank" v-if="training.file">
                            Open
                        </a>
                        <span v-else>
                            No attachment
                        </span>
                    </div>
                </div>
            </div>
            <div class="vtao-test-info" v-if="training.is_test_exam">
                <div class="row-margin">
                    <div class="vtao-tutorial col-7">
                        Tutorial
                    </div>
                    <div class="vtao-questions-number col-2">
                        Questions number
                    </div>
                    <div class="vtao-pass-score col-2">Success threshold</div>
                    <div class="vtao-time col-1">Time</div>
                </div>
                <div class="row-margin">
                    <div class="vtao-tutorial col-7">
                        <b-form-input v-model="tutorial.title" readonly />
                    </div>
                    <div class="vtao-questions-number col-2">
                        <b-form-input v-model="training.question_number" readonly />
                    </div>
                    <div class="vtao-pass-score col-2">
                        <b-form-input v-model="training.pass_score" readonly />
                    </div>
                    <div class="vtao-time col-1">
                        <b-form-input v-model="training.exam_time" readonly />
                    </div>
                </div>
            </div>
            <div class="vtao-participants" v-if="participants">
                <h5>
                    Participants
                </h5>
                <table class="table training-table">
                    <tr>
                        <th>Name</th>
                        <th>Training date</th>
                        <th v-if="training.is_test_exam">Score</th>
                        <th v-if="training.is_test_exam">Attempts</th>
                        <th>Last certification</th>
                        <th>Status</th>
                        <th v-if="!training.is_test_exam"></th>
                    </tr>
                    <tr v-for="(p, i) in participants" v-bind:key="i" v-if="p.participant" :class="'training-status-' + p.status">
                        <td>
                            {{ p.participant.NAME }}
                        </td>
                        <td>
                            {{ p.start_date }}
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ p.score ? (p.score + '%') : '-' }}
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ p.attempt !== null ? p.attempt : '-' }}
                        </td>
                        <td>
                            {{ p.update_at ? p.update_at.substring(0, 10) : '-'}}
                        </td>
                        <td>
                            {{ getStatusName(p.status) }}
                        </td>
                        <td v-if="!training.is_test_exam" class="control-buttons">
                            <b-btn variant="outline-primary" v-if="p.status === 1" @click="markAsPassed(p.id)">
                                Marked as passed
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </div>
            <h5>
                Observers
            </h5>
            <div class="vtao-observers">
                <span class="vtao-observer" v-for="(o, i) in training.observers">
                    {{ o.user.NAME }}
                </span>
            </div>
            <div class="vtao-buttons">
                <b-btn variant="outline-danger">
                    Save as PDF
                </b-btn>
                <b-btn variant="outline-success" @click="$emit('close', true)">
                    Close
                </b-btn>
            </div>
        </div>
    </div>
</template>
<script>
import { getStatus, getTrainingStatus } from '../../js/statuses'
export default{
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data () {
    return {

    }
  },
  computed: {
    training () {
      return this.$store.state.observedTrainings.find(t => t.id === this.id)
    },
    tutorial () {
      return this.training ? this.training.tutorial : null
    },
    participants () {
      return this.training ? this.training.participants : null
    }
  },
  mounted () {

  },
  methods: {
    getStatusName: getStatus,
    markAsPassed (id) {
      this.axios.put('/participants/' + id, {
        status: 2
      }).then(() => {
        this.$store.commit('updateParticipatingTraining', {
          id: id,
          props: {
            status: 2
          }
        })
        for (let i = 0; i < this.participants.length; i++) {
          if (this.participants[i].id === id) {
            let temp = this.participants[i]
            temp.status = 2
            this.$set(this.participants, i, temp)
          }
        }
        this.$store.commit('updateObservedTraining', {
          id: this.id,
          participants: this.participants
        })
        this.$forceUpdate()
      })
    },
    getTrainingStatus: getTrainingStatus
  }
}
</script>
<style>
    .vtao-headline{
        border-bottom: 1px solid #20202020;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
    .vtao-training-id, .vtao-training-status{
        border: 1px solid #10101040;
        padding: .2rem .4rem;
        margin-left: 2rem;
        margin-right: 2rem;
        border-radius: 5px;
        box-shadow: 0 1px #20202020;
    }
    .vtao-body{
        text-align: left;
    }
    .vtao-observers{
        list-style-type: circle;
        flex-wrap: wrap;
        display: flex;
    }
    .vtao-observer{
        padding: .3rem .5rem;
        border: 1px solid #10101040;
        border-radius: 6px;
        margin: .3rem .7rem;
    }
    .vtao-observer::before{
        content: '\25CF';
    }
    .vtao-buttons{
        text-align: right;
    }
</style>
