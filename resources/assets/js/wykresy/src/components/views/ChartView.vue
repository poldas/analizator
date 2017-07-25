<template>
    <v-app id="panel">
        <v-navigation-drawer temporary light v-model="drawer" overflow>
            <v-list class="pa-0">
                <v-list-tile tag="div">
                    <v-list-tile-content>
                        <v-list-tile-title>John Leider</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-btn icon light @click.native.stop="drawer = !drawer">
                            <v-icon>clear</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
            <v-list class="pt-0" dense>
                <v-divider></v-divider>
                <router-link v-for="item in items" :key="item.href" :to="item.href" class="list__tile">
                    <v-list-tile-action>
                        <v-icon dark>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </router-link>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed class="light-blue darken-1" dark>
            <v-toolbar-side-icon light @click.native.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>Budżet Alert</v-toolbar-title>
        </v-toolbar>
        <main>
            <router-view></router-view>
        </main>
        <v-footer>sdsd</v-footer>
    </v-app>
</template>
<script type="text/babel">
    import ChartItem from '../ChartItem.vue'
    export default {
        components: {
            ChartItem
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
    @import './../../../node_modules/vuetify/src/stylus/main.styl'
</style>