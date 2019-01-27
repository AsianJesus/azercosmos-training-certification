<template>
    <div class="add-participants-component">
        <div class="add-participants-headline">
            <h4>
                {{ training ? training.title : ''}}
            </h4>
        </div>
        <div class="add-participants-body">
            <div v-for="(p, i) in participants" v-bind:key="i">
                <div class="add-participant-name">
                    {{ getUserName(p.participant_id) }}
                </div>
                <div class="add-participant-dates">
                    Start date
                    <b-form-input type="date" v-model="participants[i].start_date" :state="errors[i].start_date ? false : null">

                    </b-form-input>
                    End date
                    <b-form-input type="date" v-model="participants[i].end_date" :state="errors[i].end_date ? false : null">

                    </b-form-input>
                </div>
                <div class="">
                    <b-btn variant="outline-danger" @click="deleteParticipant(i)">
                        Delete
                    </b-btn>
                </div>
                <hr>
            </div>
            <div v-if="error">
                <h5 style="color: red;">
                    {{ error }}
                </h5>
            </div>
            <div>
                <model-select :options="usersOptions" @input="addParticipant"></model-select>
            </div>
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
export default{
  components: {
    ModelSelect
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
        !this.currentParticipants.some(p => p === x.value))
    },
    errors () {
      return this.participants.map(x => {
        return {
          start_date: !(x.start_date && x.start_date < x.end_date),
          end_date: !(x.end_date && x.start_date < x.end_date)
        }
      })
    },
    training () {
      return this.$store.state.trainings.observing[this.id]
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
      if (!this.participants.length){
        this.error = 'No users to add!'
        return
      }
      if (this.errors.some(e => Object.values(e).some(er => er))) {
        this.error = 'Some fields are filled wrong'
        return
      }
      this.error = ''
      this.axios.put('/trainings/' + this.id, {
        p_to_add: this.participants
      }).then(response => {
        this.$store.commit('updateTraining', response.data)
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

</style>
