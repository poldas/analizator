<template>
<main-layout :key="'main'">
    <h1>Strona Wykresy</h1>
    <chart-list :chart-data="series" options="options"></chart-list>
</main-layout>
</template>
<script>
import ChartList from '~components/ChartList.vue'
import MainLayout from '~layouts/Main.vue'
    export default {
        components: {
            ChartList,
            MainLayout
        },
        computed: {
//            seriesData() {
//                let keys = Object.keys(this.item.series)
//                let series = keys.map((i) => {
//                    return this.item.series[i]
//                })
////        console.log(series)
////        if(!series) return []
//                return series
//            },
        },
        data () {
            return {
                drawer: true,
                items: [
                    {
                        name: "cos",
                        href: '#',
                        icon: 'poiglphymocn',
                        title: "Hi ho are"
                    }
                ],
                options: {
                    name: "nazwa wykresu to średnia",
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        type: 'category'
                    },
                    tooltip: {
                        shared: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.1,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    }
                },
                series: [1,2,3]
            }
        }
    }
</script>
<style lang="stylus">
    @import url('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin-ext')
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons')
    @import '../../node_modules/vuetify/src/stylus/main.styl'
</style>