<template>
    <div class="view-training-as-participant-component">
        <div class="vtapc-header">
            <h4>
                {{ training.title }}
            </h4>
        </div>
        <div class="vtapc-body">
            <div class="vtapc-description">
                <p>
                    {{ training.description }}
                </p>
            </div>
            <div class="vtapc-reference">
                <h6>References: </h6>
                <div>
                    {{ training.reference }}
                </div>
            </div>
            <div class="vtapc-tutorial" v-if="tutorial">
                Name: {{ tutorial.title }}
            </div>
            <div class="vtapc-participant">
                <table class="table">
                    <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th v-if="training.is_test_exam">
                            Score
                        </th>
                        <th v-if="training.is_test_exam">
                            Attempt
                        </th>
                        <th>Status</th>
                        <th v-if="participant.status < 2"></th>
                    </tr>
                    <tr>
                        <td>{{ participant.start_date }}</td>
                        <td>{{ participant.end_date }}</td>
                        <td v-if="training.is_test_exam">
                            {{ participant.score }}
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ participant.attempt }}
                        </td>
                        <td>{{ getStatusName(participant.status) }}</td>
                        <td v-if="participant.status < 2">
                            <b-btn variant="outline-success" v-if="participant.status === 0" @click="verify">
                                Verify
                            </b-btn>
                            <b-btn variant="outline-success" v-else-if="participant.status === 1">
                                Pass exam
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import { getStatus } from '../../js/statuses'
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
    participant () {
      return this.$store.state.trainingParticipating[this.id]
    },
    training () {
      return this.participant ? this.participant.training : null
    },
    tutorial () {
      return this.training ? this.training.tutorial : null
    }
  },
  methods: {
    getStatusName: getStatus,
    verify () {
      this.axios.put('/participants/' + this.id, {
        status: 1
      }).then(() => {
        this.$store.commit('updateParticipatingTraining', {
          id: this.id,
          props: {
            status: 1
          }
        })
        setTimeout(() => {
          this.$forceUpdate()
        }, 1000)
      })
    }
  }
}
</script>
<style>
</style>
