<template>
  <v-layout row wrap align-center justify-center py-4>
    <v-flex xs11 sm10 md8 class="pa-0">
      <v-card class="px-4 pt-4 mt-3 light-blue darken-1 elevation-0" dark>
        <v-icon class="green--text text--darken-2" v-if="true">done</v-icon>
        asdasddasd
        {{chartData}}
      </v-card>
    </v-flex>
  </v-layout>
</template>
<script type="text/babel">
  import ChartItem from './ChartItem.vue'
  export default {
    props: {
      chartData: {
        required: true,
        type: Array
      }
    },
    data() {
      return {
        search: 'średnia punkty'
      }
    },
    methods: {
      beforeEnter: function (el) {
        el.style.opacity = 0
        el.style.height = 0
      },
      enter: function (el, done) {
        var delay = el.dataset.index * 150
      },
      leave: function (el, done) {
        var delay = el.dataset.index * 150
      }
    },
    computed: {
      list() {
        if(!this.chartData.length) return []
        if(!this.search.length) return this.chartData
        let serches = this.search.split(',')
        return this.chartData.filter((item) => {
          let tags = Object.keys(item.tags)
          let hit = serches.some((i) => tags.join(',').toLowerCase().indexOf(i.toLowerCase().trim()) !== -1)
          return hit
        })
      }
    },
    components: {
//      ChartItem
    }
  }
</script>
<style>
  /* Enter and leave animations can use different */
  /* durations and timing functions.              */
  .slide-fade-enter-active {
    transition: all .3s ease;
  }
  .slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active for <2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
  }

  .list-enter-active, .list-leave-active {
    transition: all 1s;
  }
  .list-enter, .list-leave-to /* .list-leave-active for <2.1.8 */ {
    opacity: 0;
    transform: translateY(30px);
  }
  .list-move {
    transition: transform 1s;
  }
</style>