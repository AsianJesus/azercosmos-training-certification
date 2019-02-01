<template>
    <div class="countdown-component">
        <div>
            {{ label }}
        </div>
        <div>
            <span v-if="showHours">
                {{ hoursToShow }}
                <span v-if="showMinutes || showSeconds">
                    :
                </span>
            </span>
                <span v-if="showMinutes">
                {{ minutesToShow }}
                <span v-if="showSeconds">
                    :
                </span>
            </span>
                <span v-if="showSeconds">
                {{ secondsToShow }}
            </span>
        </div>
    </div>
</template>
<script>
export default{
  props: {
    hours: {
      type: Number,
      default: 0
    },
    minutes: {
      type: Number,
      default: 0
    },
    seconds: {
      type: Number,
      default: 0
    },
    value: {
      type: Boolean,
      default: true
    },
    interval: {
      type: Number,
      default: 1000
    },
    label: {
      type: String,
      default: 'Countdown'
    },
    showHours: {
      type: Boolean,
      default: true
    },
    showMinutes: {
      type: Boolean,
      default: true
    },
    showSeconds: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    hoursToShow () {
      let value = Math.floor(this.remainingSeconds / 3600)
      return value > 0 ? (value >= 10 ? value.toString() : '0' + value.toString()) : '00'
    },
    minutesToShow () {
      let value = Math.floor(this.remainingSeconds / 60)
      if (this.showHours) {
        value = value % 60
      }
      return value > 0 ? (value >= 10 ? value.toString() : '0' + value.toString()) : '00'
    },
    secondsToShow () {
      let value
      if (!this.showMinutes) {
        if (this.showHours) {
          value = Math.floor(this.remainingSeconds) % 3600
        } else {
          value = Math.floor(this.remainingSeconds)
        }
      } else {
        value = Math.floor(this.remainingSeconds) % 60
      }
      return value > 0 ? (value >= 10 ? value.toString() : '0' + value.toString()) : '00'
    }
  },
  data () {
    return {
      remainingSeconds: null,
      intervalObject: null
    }
  },
  mounted () {
    this.remainingSeconds = this.hours * 3600 + this.minutes * 60 + this.seconds
    this.intervalObject = setInterval(this.makeStep, this.interval)
  },
  methods: {
    makeStep () {
      if (this.value) {
        this.remainingSeconds -= this.interval / 1000
        if (this.remainingSeconds <= 0) {
          this.end()
        }
      }
    },
    end () {
      this.$emit('end')
      this.$emit('value', false)
    }
  },
  beforeDestroy () {
    clearInterval(this.intervalObject)
  }
}
</script>
<style>

</style>
