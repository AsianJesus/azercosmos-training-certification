<template>
  <div id="app" class="container">
    <div class="row">
      <div class="col-12">
        <router-view/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
  mounted () {
    this.loadTutorials()
    this.loadUsers()
  },
  methods: {
    loadTutorials () {
      this.axios.get('/users/1/tutorials').then(response => {
        console.log(response.data)
        this.$store.commit('setTutorials', {
          type: 'my',
          tutorials: response.data.my
        })
        this.$store.commit('setTutorials', {
          type: 'observing',
          tutorials: response.data.observing
        })
        this.$store.commit('setTutorials', {
          type: 'moderating',
          tutorials: response.data.moderating
        })
        // this.$store.commit('setTutorials', response.data)
      }).catch(err => {
        alert('Error occurred')
        console.log(err)
      })
    },
    loadUsers () {
      this.axios.get('/users').then(response => {
        this.$store.commit('setUsers', response.data)
        console.log(response.data)
      })
    }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
  min-height: 90vh;
}
</style>
