<template>
    <div class="add-participants-component">
        <div class="add-participants-headline" v-if="training">
            <div class="add-participants-info">
                <span class="add-participants-id">
                    Training #{{ training.id }}
                </span>
            </div>
            <h4>
                {{ training.title}}
            </h4>
        </div>
        <div class="add-participants-body">
            <div v-if="error">
                <h5 style="color: red;">
                    {{ error }}
                </h5>
            </div>
            <new-participant-component v-model="participants" :showError="error !== null" v-bind:options="usersOptions"
                                       @error="participantsErrors = $event">
            </new-participant-component>
        </div>
        <div class="add-participants-buttons">
            <b-btn @click="save" variant="outline-success">
                Save
            </b-btn>
        </div>
    </div>
</template>
<script>
import { ModelSelect } from 'vue-search-select'
import NewParticipantComponent from './NewParticipantsComponent.vue'
export default{
  components: {
    ModelSelect,
    NewParticipantComponent
  },
  props: {
    id: {
      required: true,
      type: Number
    }
  },
  data () {
    return {
      participants: [],
      participantsErrors: null,
      error: null
    }
  },
  computed: {
    currentParticipants () {
      let participants = this.training ? this.training.participants : []
      return participants.map(x => x.participant_id)
    },
    usersOptions () {
      return this.$store.getters.usersOptions.filter(x => !this.participants.some(p => p.participant_id === x.value) &&
        !this.currentParticipants.some(p => p === x.value) && x.value !== this.$store.state.userID)
    },
    training () {
      return this.$store.state.observedTrainings.find(t => t.id === this.id)
    }
  },
  watch: {
    participants () {
      this.error = ''
    }
  },
  methods: {
    addParticipant (id) {
      this.participants.push({
        participant_id: id,
        start_date: null,
        end_date: null
      })
    },
    getUserName (id) {
      return this.$store.state.users.find(x => x.ID === id).NAME
    },
    deleteParticipant (index) {
      this.participants.splice(index, 1)
    },
    save () {
      if (!this.participants.length) {
        this.error = 'No users to add!'
        return
      }
      if (this.participantsErrors) {
        this.error = 'Some fields are filled wrong'
        return
      }
      this.error = null
      this.axios.put('/trainings/' + this.id, {
        p_to_add: this.participants
      }).then(response => {
        this.$store.commit('updateObservedTraining', response.data)
        if (this.participants.some(x => x.participant_id === this.$store.state.userID)) {
          let t = response.data.participants.find(x => x.participant_id === this.$store.state.userID)
          t.training = response.data
          this.$store.commit('addParticipatingTraining', t)
        }
        this.$emit('close', true)
      })
    }
  }
}
</script>
<style>
.add-participants-id{
    border: 1px solid #10101040;
    padding: .2rem .4rem;
    margin-left: 2rem;
    margin-right: 2rem;
    border-radius: 5px;
    box-shadow: 0 1px #20202020;
}
.add-participants-buttons{
    margin-top: .5rem;
}
</style>
