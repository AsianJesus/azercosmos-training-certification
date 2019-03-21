/**
 * Created by fruit on 2/4/2019.
 */

const statuses = {
  0: 'Not confirmed',
  1: 'Not finished',
  2: 'Finished'
}

const participatingFields = {
  'Title': 'training.title',
  'Status': {
    field: 'status',
    callback: (value) => {
      console.log(statuses, value)
      return statuses[value]
    }
  },
  'Record Date': {
    field: 'created_at',
    callback: (value) => {
      return value.substr(0, 10)
    }
  },
  'Start': 'start_date',
  'Deadline': 'end_date',
  'Is test exam?': {
    field: 'training.is_test_exam',
    callback: (value) => {
      return value ? 'Yes' : 'No'
    }
  },
  'Examiner': 'training.originator.NAME'
}

const observervingFields = {
  'ID': 'id',
  'Title': 'title',
  'Is test exam': {
    field: 'is_test_exam',
    callback: (value) => {
      return value ? 'Yes' : 'No'
    }
  },
  'Record date': 'created_at',
  'Last change date': 'updated_at',
  'Trainer': 'originator.NAME',
  'Tutorial': {
    field: 'tutorial',
    callback: (value) => {
      return value ? value.title : ''
    }
  },
  'Exam time': 'exam_time',
  'Questions count': 'question_number',
  'Success threshold': 'pass_score'
}

const participantFields = {
  'Name': 'participant.NAME',
  'Status': {
    field: 'status',
    callback (value) {
      return statuses[value]
    }
  },
  'From': 'start_date',
  'To': 'end_date',
  'Attempts': 'attempt',
  'Score': 'score',
  'Last try': 'updated_at'
}

const tutorialFields = {
  'ID': 'id',
  'Title': 'title',
  'System': 'system',
  'Creation time': 'created_at',
  'Last update': 'updated_at',
  'Verified questions': 'verified_questions_count',
  'Questions count': 'questions_count'
}

export { participatingFields, observervingFields, participantFields, tutorialFields }
