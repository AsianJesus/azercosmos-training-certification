<template>
    <div class="bcards-filter">
        <div class="bcards-filter-input-holder">
            <b-btn>
                <v-icon name="search" />
            </b-btn>
            <b-form-input v-model="temp"
                          @input="updateFilter"
                          v-if="selectedItem === i && !f.options"
                          class="bcards-filter-input"
                          v-for="(f, i) in value" v-bind:key="'st' + i" />
            <b-form-select v-if="selectedItem === i && f.options"
                           v-model="temp" v-bind:key="'ss' + i"
                           :options="getOptions(value[i])"
                           class="bcards-filter-input"
                           @input="updateFilter"
                           v-for="(f, i) in value" />
                <!--<option :value="i" v-for="(f, i) in value">
                    {{ f.text }}
                </option>-->
                <!--<option :value="null">
                    Select filter
                </option>
                <option :value="o.value" v-for="(o, index) in f.options" v-bind:key="index">
                    {{ o.text }}
                </option>-->
            <b-form-select v-model="selectedItem"
                           class="bcards-filter-input"
                           :options="options" />
        </div>
        <div class="filter-values">
            <div class="filter-values-item"
                 v-for="(f, i) in value"
                 v-bind:key="i"
                 v-if="f.value !== null && f.value !== ''"
                 @click="deleteValue(i)">
                {{ f.text }} - {{ getValue(f) }}
            </div>
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
  computed: {
    options () {
      return this.value.map((v, i) => {
        return {
          text: v.text,
          value: i
        }
      })
    }
  },
  data () {
    return {
      selectedItem: 0,
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
    },
    deleteValue (index) {
      this.value[index].value = null
      if (index === this.selectedItem) this.temp = null
      this.$emit('input', this.value)
    },
    getValue (filter) {
      return filter.options ? filter.options.find(x => x.value === filter.value).text : filter.value
    },
    getOptions (filter) {
      if (!filter.options) {
        return []
      }
      return filter.options
    }
  }
}
</script>
<style>
    .filter-values{
        margin: 1rem auto;
    }
    .filter-values-item{
        display: inline;
        background-color: #c1c1c1;
        padding: .4rem .6rem;
        margin: 0 .3rem;
        border-radius: 5px;
        text-transform: capitalize;
        cursor: pointer;
    }
    .bcards-filter-input-holder {
        text-align: left;
    }
    .bcards-filter-input {
        width: max-content;
        display: inline !important;
    }
</style>
