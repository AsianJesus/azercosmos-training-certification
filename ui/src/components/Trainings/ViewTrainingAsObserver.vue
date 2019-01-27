<template>
    <div class="view-training-as-observer">
        <div class="vtao-headline">
            {{ training.title }}
        </div>
        <div class="vtao-body">
            <div class="vtao-description">
                <p>
                    {{ training.description }}
                </p>
            </div>
            <div class="vtao-tutorial-info" v-if="tutorial">
                Tutorial: {{ tutorial.title }}
            </div>
            <div class="vtao-participants" v-if="participants">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th v-if="training.is_test_exam">Score</th>
                        <th v-if="training.is_test_exam">Attempts</th>
                        <th>Status</th>
                        <th v-if="!training.is_test_exam"></th>
                    </tr>
                    <tr v-for="(p, i) in participants" v-bind:key="i" v-if="p.participant">
                        <td>
                            {{ p.participant.NAME }}
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ p.score }}
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ p.attempt }}
                        </td>
                        <td>
                            {{ getStatusName(p.status) }}
                        </td>
                        <td v-if="!training.is_test_exam">
                            <b-btn variant="outline-primary" v-if="p.status === 1" @click="markAsPassed(p.id)">
                                Marked as passed
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
    training () {
      return this.$store.state.trainings.observing[this.id]
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
        this.$store.commit('updateTraining', {
          id: this.id,
          participants: this.participants
        })
        this.$forceUpdate()
      })
    }
  }
}
</script>
<style>

</style>
