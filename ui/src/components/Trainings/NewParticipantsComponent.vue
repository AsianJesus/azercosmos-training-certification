<template>
    <div class="new-participants-component">
        <div class="new-participant-holder">
            <model-select :options="usersOptions" @input="addParticipant" placeholder="Participants"></model-select>
        </div>
        <div class="new-participants-list">
            <div v-for="(p, i) in participants" v-bind:key="i">
                <div class="new-participant-name"> {{ getUserName(p.participant_id) }}</div>
                <div class="new-participant-dates">
                    Start
                    <b-form-input type="date" v-model="participants[i].start_date"
                                  :state="showError && errors[i].start ? false : null">

                    </b-form-input>
                    End
                    <b-form-input type="date" v-model="participants[i].end_date"
                                  :state="showError && errors[i].end ? false : null">

                    </b-form-input>
                </div>
                <div class="new-participant-buttons">
                    <b-btn @click="deleteParticipant(i)" variant="outline-danger">
                        Delete
                    </b-btn>
                </div>
            </div>
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
    showError: {
      type: Boolean,
      default: false
    },
    participantsInitial: {
      type: Array,
      default () {
        return []
      }
    }
  },
  data () {
    return {
      participants: []
    }
  },
  mounted () {
    this.participants = this.participantsInitial
  },
  watch: {
    participants (val) {
      this.$emit('input', val)
    },
    errors (val) {
      this.$emit('error', val.some(x => Object.values(x).some(y => y)))
    },
    participantsInitial (val) {
      this.participants = val
    }
  },
  computed: {
    usersOptions () {
      return this.$store.getters.usersOptions.filter(x => x.value !== this.$store.state.userID &&
        !this.participants.some(p => p.participant_id === x.value))
    },
    errors () {
      return this.participants.map(p => {
        return {
          start: !p.start_date,
          end: !p.end_date || p.start_date >= p.end_date
        }
      })
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
    deleteParticipant (index) {
      this.participants.splice(index, 1)
    },
    getUserName (id) {
      return this.$store.state.users.find(x => x.ID === id).NAME
    }
  }
}
</script>
<style>

</style>
