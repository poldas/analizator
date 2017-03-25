<template>
  <div class="wykresy">
    <ChartList :items="chartItems"></ChartList>
  </div>
</template>
<script>
import ChartList from '../components/ChartList.vue'
import {mapGetters} from 'vuex';
  export default {
    components: {
      ChartList
    },
    computed: Object.assign({}, ...mapGetters({
      chartItems: 'wykresy'
    })),
    created() {
      this.$store.dispatch('fetchData', this.$route.params.id_analiza)
    }
  }
</script>
<style scoped>
  @import '../../node_modules/vuetify/dist/vuetify.min.css';
</style>