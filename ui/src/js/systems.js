/**
 * Created by fruit on 1/23/2019.
 */

const systems = [
  'AZERSKY', 'AZERSPACE'
]

var systemOptions = systems.map(x => {
  return {
    text: x,
    value: x.toLowerCase()
  }
})
systemOptions.unshift({
  text: 'Please, select system',
  value: null
})

export { systems, systemOptions }