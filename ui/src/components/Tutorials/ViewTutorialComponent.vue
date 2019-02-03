<template>
    <div class="view-tutorial-component">
        <div class="view-tutorial-headline">
            <span class="view-tutorial-id">
                Tutorial #{{ tutorial.id }}
            </span>
        </div>
        <div class="view-tutorial-row row-margin">
            <div class="view-tutorial-title col-6">
                Tutorial
                <b-form-input v-model="tutorial.title" readonly />
            </div>
            <div class="view-tutorial-system col-6">
                System:
                <b-form-input v-model="tutorial.system" readonly />
            </div>
        </div>
        <div class="view-tutorial-row row-margin">
            <div class="view-tutorial-date col-6">
                Creation date
                <b-form-input v-model="tutorial.created_at" readonly />
            </div>
        </div>
        <div class="view-tutorial-row row-margin">
            <div class="view-tutorial-users col-8">
            <h5>
                Moderators:
            </h5>
            <div class="view-tutorial-moderators" v-if="tutorial.moderators && tutorial.moderators.length">
                <div v-for="(m, i) in tutorial.moderators" v-bind:key="i" class="view-tutorial-moderator">
                    {{ m.user.NAME }}
                </div>
            </div>
            <div v-else>
                <h6>
                    There are no moderators
                </h6>
            </div>
            <h5>
                Observers
            </h5>
            <div class="view-tutorial-observers" v-if="tutorial.observers && tutorial.observers.length">
                <div v-for="(o, i) in tutorial.observers" v-bind:key="i" class="view-tutorial-observer">
                    {{ o.user.NAME }}
                </div>
            </div>
            <div v-else>
                <h6>
                    There are no observers
                </h6>
            </div>
        </div>
            <div class="view-tutorial-questions col-4">
                <span class="view-tutorial-questions-count">
                    Questions: {{ tutorial.verified_questions_count }} / {{ tutorial.questions_count }}
                </span>
            </div>
        </div>
        <div class="view-tutorial-close-button">
            <b-btn @click="$emit('close')" variant="outline-danger">
                Close
            </b-btn>
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
.view-tutorial-component{
    text-align: left;
}
.view-tutorial-headline{
    text-align: center;
    border-bottom: 1px solid #303030;
    padding-top: .7rem;
    padding-bottom: .7rem;
    margin-bottom: 1rem;
}
.view-tutorial-id{
    padding: .3rem .5rem;
    border: 1px solid #303030;
    border-radius: 5px;
    box-shadow: 0 1px 2px 2px #30303040;
}
.view-tutorial-row{
    margin-top: 1rem;
    margin-bottom: 1rem;
}
.view-tutorial-system{
    font-style: italic;
}
.view-tutorial-moderators, .view-tutorial-observers{
    display: flex;
}
.view-tutorial-moderator, .view-tutorial-observer{
    padding: .3rem .7rem;
    border: 1px solid #30303050;
    border-radius: 10px;
    flex: 0 1 25%;
    margin: .2rem .3rem;
}
.view-tutorial-moderator::before, .view-tutorial-observer::before{
    content: '\25CF';
}
.view-tutorial-questions-count{
    border: 1px solid #30303060;
    border-radius: 5px;
    padding: .4rem .6rem;
}
.view-tutorial-close-button{
    text-align: right;
}
</style>
