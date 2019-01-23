<template>
    <div class="view-tutorial-component">
        <div class="view-tutorial-close-button">
            <b-btn @click="$emit('close')" variant="outline-danger">
                Close
            </b-btn>
        </div>
        <div class="view-tutorial-title">{{ tutorial.title }}</div>
        <div class="view-tutorial-system">{{ tutorial.system }}</div>
        <div class="view-tutorial-date">
            Created at {{ tutorial.created_at }}
        </div>
        <div class="view-tutorial-users">
            <div class="view-tutorial-moderators" v-if="tutorial.moderators">
                Moderators:
                <ul>
                    <li v-for="(m, i) in tutorial.moderators" v-bind:key="i">
                        {{ m.user.NAME }}
                    </li>
                </ul>
            </div>
            <div class="view-tutorial-observers" v-if="tutorial.observers">
                Observers:
                <ul>
                    <li v-for="(o, i) in tutorial.observers" v-bind:key="i">
                        {{ o.user.NAME }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="view-tutorial-questions">
            <h6>
                Questions
            </h6>
            <div class="view-tutorial-questions-count">
                {{ tutorial.verified_questions_count }} / {{ tutorial.questions_count }}
            </div>
        </div>
    </div>
</template>
<script>
export default{
  props: {
    tutorial: {
      type: Object,
      required: true
    }
  }
}
</script>
<style>

</style>
