<template>
    <div class="view-training-as-participant-component">
        <div class="vtapc-headline">
            <span class="vtapc-training-id">
                Training {{ '#' + training.id }}
            </span>
            <span class="vtapc-training-status">
                Status: {{ getTrainingStatus(training) }}
            </span>
        </div>
        <div class="vtapc-body">
            <div class="row-margin vtapc-info-element">
                <div class="col-6">Trainer</div>
                <div class="col-4 offset-2">Training record date</div>
                <div class="col-6">
                    <b-form-input v-model="training.originator.NAME" readonly/>
                </div>
                <div class="col-4 offset-2">
                    <b-form-input v-model="training.created_at" readonly/>
                </div>
            </div>
            <div class="vtapc-title vtapc-info-element">
                Title
                <b-form-input v-model="tutorial_title" readonly/>
            </div>
            <div class="vtapc-description vtapc-info-element">
                Description
                <b-form-input v-model="training.description" readonly/>
            </div>
            <div>
                <div class="row-margin">
                    <div class="vtapc-reference col">
                        Reference
                    </div>
                    <div class="vtapc-attachment col-3">
                        Attachment
                    </div>
                </div>
                <div class="row-margin">
                    <div class="vtapc-reference col">
                        <b-form-input v-model="training.reference" readonly/>
                    </div>
                    <div class="col-3">
                        <a :href="$store.state.serverURL + '/' + training.file.path" target="_blank"
                           v-if="training.file">
                            Open
                        </a>
                        <span v-else>
                            No attachment
                        </span>
                    </div>
                </div>
            </div>
            <div class="vtapc-test-info vtapc-info-element" v-if="training.is_test_exam">
                <div class="row-margin">
                    <div class="vtapc-tutorial col-7">
                        Tutorial
                    </div>
                    <div class="vtapc-questions-number col-2">
                        Questions number
                    </div>
                    <div class="vtapc-pass-score col-2">Success threshold</div>
                    <div class="vtapc-time col-1">Time</div>
                </div>
                <div class="row-margin">
                    <div class="vtapc-tutorial col-7">
                        <b-form-input v-model="tutorial_title" readonly/>
                    </div>
                    <div class="vtapc-questions-number col-2">
                        <b-form-input v-model="training.question_number" readonly/>
                    </div>
                    <div class="vtapc-pass-score col-2">
                        <b-form-input v-model="training.pass_score" readonly/>
                    </div>
                    <div class="vtapc-time col-1">
                        <b-form-input v-model="training.exam_time" readonly/>
                    </div>
                </div>
            </div>
            <div class="vtapc-participant">
                <table class="table">
                    <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th v-if="training.is_test_exam">
                            Score
                        </th>
                        <th v-if="training.is_test_exam">
                            Attempt
                        </th>
                        <th>Status</th>
                        <th v-if="participant.status < 2"></th>
                    </tr>
                    <tr>
                        <td>{{ participant.start_date }}</td>
                        <td>{{ participant.end_date }}</td>
                        <td v-if="training.is_test_exam">
                            {{ participant.score }}%
                        </td>
                        <td v-if="training.is_test_exam">
                            {{ participant.attempt }}
                        </td>
                        <td>{{ getStatusName(participant.status) }}</td>
                        <td v-if="participant.status < 2">
                            <b-btn variant="outline-success" v-if="participant.status === 0" @click="verify">
                                Verify
                            </b-btn>
                            <b-btn variant="outline-success" @click="showTests = true"
                                   v-else-if="training.is_test_exam && participant.status === 1">
                                Pass exam
                            </b-btn>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <transition name="fade">
            <div class="modal-window-canvas" v-if="showTests" @click="hideTests(false)">
                <div class="modal-window-holder" @click="$event.stopPropagation()">
                    <div class="modal-window-close" @click="hideTests(true)"> X </div>
                    <test-exam-component @close="hideTests($event)"
                                         :id="training.id"
                                         @fail="fail"
                                         @passed="markAsPassed"/>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
    import {getStatus, getTrainingStatus} from '../../js/statuses'
    import TestExamComponent from '../Tests/TestExamComponent.vue'

    export default {
        props: {
            id: {
                type: Number,
                required: true
            }
        },
        components: {
            TestExamComponent
        },
        data() {
            return {
                showTests: false
            }
        },
        computed: {
            participant() {
                return this.$store.state.trainingParticipating.find(t => t.id === this.id)
            },
            training() {
                return this.participant ? this.participant.training : null
            },
            tutorial() {
                return this.training ? this.training.tutorial : null
            },
            tutorial_title() {
                if (this.training.hasOwnProperty("tutorial") && this.training.tutorial != null) {
                    if (this.training.tutorial.hasOwnProperty("title")) {
                        if (typeof this.training.tutorial.title !== 'undefined') {

                            return this.training.tutorial.title;
                        }

                    }
                }
                return "";
            },

        },
        methods: {
            getStatusName: getStatus,
            getTrainingStatus: getTrainingStatus,
            verify() {
                this.axios.put('/participants/' + this.id, {
                    status: 1
                }).then(() => {
                    this.$store.commit('updateParticipantInfo', {
                        id: this.id,
                        props: {
                            status: 1
                        }
                    })
                })
            },
            hideTests(force = false) {
                if (!force && !confirm('Do you wanna close window?')) {
                    return
                }
                this.showTests = false
            },
            fail(score) {
                let currentScore = this.participant.score || 0
                this.$store.commit('updateParticipatingTraining', {
                    id: this.id,
                    props: {
                        score: Math.max(score, currentScore),
                        attempt: this.participant.attempt + 1
                    }
                })
                this.$store.commit('updateParticipantInfo', {
                    id: this.id,
                    props: {
                        score: Math.max(score, currentScore),
                        attempt: this.participant.attempt + 1
                    }
                })
            },
            markAsPassed(score) {
                this.$store.commit('updateParticipatingTraining', {
                    id: this.id,
                    props: {
                        score: score,
                        attempt: this.participant.attempt + 1
                    },
                    callback: () => this.$forceUpdate()
                })
                this.$store.commit('updateParticipantInfo', {
                    id: this.id,
                    props: {
                        score: score,
                        attempt: this.participant.attempt + 1,
                        status: 2
                    },
                    callback: () => this.$forceUpdate()
                })
            }
        }
    }
</script>
<style>
    .vtapc-headline {
        border-bottom: 1px solid #20202020;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }

    .vtapc-info-element {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .vtapc-training-id, .vtapc-training-status {
        border: 1px solid #10101040;
        padding: .2rem .4rem;
        margin-left: 2rem;
        margin-right: 2rem;
        border-radius: 5px;
        box-shadow: 0 1px #20202020;
    }

    .vtapc-body {
        text-align: left;
    }

    .vtapc-observer {
        padding: .3rem .5rem;
        border: 1px solid #10101040;
        border-radius: 6px;
    }

    .vtapc-observer::before {
        content: '\25CF';
    }
</style>
