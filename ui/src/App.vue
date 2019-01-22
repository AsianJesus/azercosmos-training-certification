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
  },
  methods: {
    loadTutorials () {
      this.axios.get('/tutorials').then(response => {
        this.$store.commit('setTutorials', response.data)
      }).catch(err => {
        alert('Error occurred')
        console.log(err)
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
