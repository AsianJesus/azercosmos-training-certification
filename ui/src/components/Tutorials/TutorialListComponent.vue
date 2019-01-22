<template>
    <div class="tutorial-list-component">
        <h4>Tutorials</h4>
        <table class="table table-hover">
            <tr>
                <th>ID</th>
                <th>Title</th>
            </tr>
            <tr v-for="(t, index) in tutorials" v-bind:key="index">
                <th>{{ t.id }}</th>
                <th>{{ t.title }}</th>
            </tr>
        </table>
    </div>
</template>
<script>

export default{
  computed: {
    tutorials () {
      return this.$store.state.tutorials
    }
  }
}
</script>
<style>

</style>
