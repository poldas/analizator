<template>
  <v-card>
    <v-card-row class="blue darken-1">
      <v-card-title class="white--text">{{item.name}}</v-card-title>
      <v-btn icon="icon" v-dropdown="{value: dropdownName}">
        <v-icon class="white--text">more_vert</v-icon>
      </v-btn>
      <v-dropdown
        :id="dropdownName"
        right
        transition="v-slide-y-transition">
        <v-dropdown-item
          v-for="menuItem in generateDoprdownOptions"
          v-bind:item="menuItem"
          key="menuItem"
          @click.native="doAction(menuItem)">
        </v-dropdown-item>
      </v-dropdown>
    </v-card-row>
    <v-card-text>
      <chart :item="item" :key="item"></chart>
      <opis-wykresu v-model="item.opis"></opis-wykresu>
    </v-card-text>
  </v-card>
</template>
<script type="text/babel">
  import OpisWykresu from './OpisWykresu.vue'
  import Chart from './Chart.vue'
  export default {
    props: ['item'],
    data () {
      return {
        clicked: '',
        popup_data: ['Toast with Callback', 'right', 4000, () => alert('Callback')]
      }
    },
    computed: {
      dropdownName() {
        return `dropdown${this.item.id}`
      },
      generateDoprdownOptions() {
        let isPrintText = this.item.show? "Dodane do druku" : "Dodaj do druku"
        return [
          { href: 'javascript:;', text: 'Zapisz', action: 'save' },
          { href: 'javascript:;', text: isPrintText, action: 'print' }
        ]
      },
    },
    mounted () {
      this.$vuetify.bus.sub('modal:close:demo-modal', this.popup)
    },
    methods: {
      popup () {
        this.$vuetify.toast.create(...this.popup_data)
      },
      doAction(menuItem) {
        this.$store.dispatch(`${menuItem.action}`, this.item)
      }
    },
    components: {
      OpisWykresu,
      Chart
    }
  }
</script>
