/**
 * Created by fruit on 1/25/2019.
 */

const statuses = {
  0: 'Not confirmed',
  1: 'Not finished',
  2: 'Finished'
}

function getStatus (id) {
  return statuses[id]
}

export {statuses, getStatus}
