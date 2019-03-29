/**
 * Created by fruit on 1/23/2019.
 */
const difficulties = {
  0: 'Easy',
  1: 'Medium',
  2: 'Hard'
}

function mapDifficulty (i) {
  return difficulties[i]
}

let difficultyOptions = Object.keys(difficulties).map((x) => {
  return {
    text: difficulties[x],
    value: parseInt(x)
  }
})

difficultyOptions.unshift({
  text: 'Please, select difficulty',
  value: null
})

export { difficulties, mapDifficulty, difficultyOptions }
