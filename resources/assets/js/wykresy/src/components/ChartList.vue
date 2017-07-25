<template>
    <div>
      <v-row v-if="!items.length" >
        <v-col xs12="xs12">
          <v-alert warning>Nie masz żadnych wykresów.</v-alert>
        </v-col>
      </v-row>

      <v-row>
        <v-col xs12="xs12">
          <v-text-input
            id="search"
            name="search"
            label="Wpisz nazwę wykresu, np. klasa A"
            v-model="search"
          ></v-text-input>
        </v-col>
      </v-row>

      <v-row>
        <!--<v-col xs6="xs6" v-for="(item, index) in list" :key="item">-->
          <!--<chart-item :item="item" key="item"></chart-item>-->
        <!--</v-col>-->
      </v-row>
    </div>
</template>
<script type="text/babel">
  import ChartItem from './ChartItem.vue'
  export default {
    props: {
      items: {
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
        if(!this.items.length) return []
        if(!this.search.length) return this.items
        let serches = this.search.split(',')
        return this.items.filter((item) => {
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