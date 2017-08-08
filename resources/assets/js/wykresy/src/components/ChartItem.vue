<template>
  <v-card class="" >
    <chart :options="chartOptions"></chart>
  </v-card>
</template>
<script type="text/babel">
  import OpisWykresu from './OpisWykresu.vue'
  import Chart from './Chart.vue'
  export default {
    props: {
        chart: {
            required: true,
            type: Object
        }
    },
    data () {
      return {
        opis: '',
        clicked: '',
        popup_data: ['Toast with Callback', 'right', 4000, () => alert('Callback')]
      }
    },
    computed: {
      chartOptions() {
        return {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Historic World Population by Region'
            },
            subtitle: {
                text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
            },
            xAxis: {
                categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' millions'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Year 1800',
                data: [107, 31, 635, 203, 2]
            }, {
                name: 'Year 1900',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2012',
                data: [1052, 954, 4250, 740, 38]
            }]
        }
      }
    },
    mounted () {
//      this.$vuetify.bus.sub('modal:close:demo-modal', this.popup)
    },
    methods: {
      popup () {
//        this.$vuetify.toast.create(...this.popup_data)
      },
      doAction(menuItem) {
//        this.$store.dispatch(`${menuItem.action}`, this.series)
      }
    },
    components: {
      OpisWykresu,
      Chart
    }
  }
</script>
