<template>
    <div class="bcards-filter row no-gutters">
        <div class="bcards-filter-input col-6">
            <b-btn :size="'sm'" :variant="'secondary'">
               Search
            </b-btn>
            <select v-model="selectedItem" style="height: 100%;">
                <option :value="i" v-for="(f, i) in value">
                    {{ f.text }}
                </option>
            </select>
            <input type="text" v-model="temp" @input="updateFilter"
                   v-if="selectedItem === i && !f.options" v-for="(f, i) in value">
            <select v-if="selectedItem === i && f.options" v-model="temp" @input="updateFilter" v-for="(f, i) in value">
                <option :value="o.value" v-for="(o, index) in f.options" v-bind:key="index">
                    {{ o.text }}
                </option>
            </select>
        </div>
    </div>
</template>
<script>
import lodash from 'lodash'
export default{
  props: {
    value: {
      type: Array,
      required: true
    }
  },
  watch: {
    selectedItem (val) {
      this.changeFilter(val)
    }
  },
  data () {
    return {
      selectedItem: null,
      temp: null
    }
  },
  methods: {
    updateFilter: lodash.debounce(function () {
      this.value[this.selectedItem].value = this.temp
      this.$emit('input', this.value)
    }, 100),
    changeFilter (index) {
      console.log(this.value[index], index)
      this.temp = this.value[index] ? this.value[index].value : null
    }
  }
}
</script>
<style>

</style>
