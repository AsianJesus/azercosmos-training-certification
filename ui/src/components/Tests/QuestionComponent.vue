<template>
    <div class="test-question-component row">
        <div class="col-2">
            <div class="test-question-result" v-if="value.isCorrect !== null">
                <div class="test-question-false" v-if="!value.isCorrect">
                    <v-icon name="times" :scale="3" color="red" />
                </div>
                <div class="test-question-correct" v-else>
                    <v-icon name="plus" :scale="3" color="green" />
                </div>
            </div>
            <div class="test-question-score">
                {{ value.difficulty }} score
            </div>
        </div>
        <div class="col">
            <div class="test-question-body">
                <div class="test-question-question">
                    <span class="test-question-number" v-if="index">
                        {{ index }}.
                    </span>
                    {{ value.question }}
                    <span v-if="value.file">
                        <a :href="$store.state.serverURl + '/' + value.file.path" target="_blank">
                            Attachment
                        </a>
                    </span>
                </div>
                <div class="test-question-answers">
                    <div class="test-question-answer" v-for="(a, i) in value.answers" v-bind:key="i">
                        <input type="radio" :name="'question_' + hash" :id="'question_' + hash + '_answer_' + i"
                               :value="value.selected === i" @input="select(i)">
                        <label :for="'question_' + hash + '_answer_' + i" class="test-question-answer-text">
                            {{ a.answer }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default{
  props: {
    value: {
      required: true,
      type: Object
    },
    index: {
      default: null,
      type: Number
    }
  },
  data () {
    return {
      hash: 1
    }
  },
  mounted () {
    this.hash = Math.random()
  },
  methods: {
    select (i) {
      this.value.selected = i
      this.$emit('input', this.value)
    }
  }
}
</script>
<style>
.test-question-number{
    font-size: 1.4rem;
}
.test-question-score{
    border: 1px solid #40404080;
    padding: .8rem;
    text-align: center;
}
</style>
