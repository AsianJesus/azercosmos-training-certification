/**
 * Created by fruit on 1/23/2019.
 */
const difficulties = {
  1: 'Easy',
  2: 'Medium',
  3: 'Hard'
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