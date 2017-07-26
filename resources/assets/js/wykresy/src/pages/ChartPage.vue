<template>
<main-layout :key="'main'">
    <h1>Strona Wykresy</h1>
    <chart-list :chart-data="apiResponse"></chart-list>
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
                apiResponse: [
                    {
                        "id": 409,
                        "id_analiza": 5,
                        "name": "Średnia zadań, klasa A płeć",
                        "labels": {
                            "1": "1",
                            "2": "2",
                            "3": "3",
                            "4": "4",
                            "5": "5",
                            "6": "6",
                            "7": "7",
                            "8": "8",
                            "9": "9",
                            "10": "10",
                            "11": "11",
                            "12": "12",
                            "13": "13",
                            "14.1": "14.1",
                            "14.2": "14.2",
                            "14.3": "14.3",
                            "14.4": "14.4",
                            "14.5": "14.5",
                            "14.6": "14.6"
                        },
                        "series": [
                            {
                                "data": [
                                    [
                                        "1",
                                        0.928571
                                    ],
                                    [
                                        "2",
                                        0.928571
                                    ],
                                    [
                                        "3",
                                        0.571429
                                    ],
                                    [
                                        "4",
                                        0.857143
                                    ],
                                    [
                                        "5",
                                        1
                                    ],
                                    [
                                        "6",
                                        0.785714
                                    ],
                                    [
                                        "7",
                                        0.642857
                                    ],
                                    [
                                        "8",
                                        0.428571
                                    ],
                                    [
                                        "9",
                                        0.857143
                                    ],
                                    [
                                        "10",
                                        0.857143
                                    ],
                                    [
                                        "11",
                                        0.642857
                                    ],
                                    [
                                        "12",
                                        0.642857
                                    ],
                                    [
                                        "13",
                                        0.857143
                                    ],
                                    [
                                        "14.1",
                                        0.517857
                                    ],
                                    [
                                        "14.2",
                                        0.714286
                                    ],
                                    [
                                        "14.3",
                                        0.571429
                                    ],
                                    [
                                        "14.4",
                                        0.464286
                                    ],
                                    [
                                        "14.5",
                                        0.642857
                                    ],
                                    [
                                        "14.6",
                                        0.5
                                    ]
                                ],
                                "name": "kobieta"
                            },
                            {
                                "data": [
                                    [
                                        "1",
                                        0.666667
                                    ],
                                    [
                                        "2",
                                        0.888889
                                    ],
                                    [
                                        "3",
                                        0.777778
                                    ],
                                    [
                                        "4",
                                        1
                                    ],
                                    [
                                        "5",
                                        1
                                    ],
                                    [
                                        "6",
                                        0.333333
                                    ],
                                    [
                                        "7",
                                        0.777778
                                    ],
                                    [
                                        "8",
                                        0.333333
                                    ],
                                    [
                                        "9",
                                        0.888889
                                    ],
                                    [
                                        "10",
                                        0.777778
                                    ],
                                    [
                                        "11",
                                        0.777778
                                    ],
                                    [
                                        "12",
                                        0.777778
                                    ],
                                    [
                                        "13",
                                        0.777778
                                    ],
                                    [
                                        "14.1",
                                        0.333333
                                    ],
                                    [
                                        "14.2",
                                        0.666667
                                    ],
                                    [
                                        "14.3",
                                        0.666667
                                    ],
                                    [
                                        "14.4",
                                        0.5
                                    ],
                                    [
                                        "14.5",
                                        0.111111
                                    ],
                                    [
                                        "14.6",
                                        0.555556
                                    ]
                                ],
                                "name": "mężczyzna"
                            }
                        ],
                        "options": {
                            chart: {
                                type: 'bar'
                            },
                            xAxis: {
                                type: 'category'
                            }
                        },
                        "labels": {},
                        "tags": {},
                        "opis": ""
                    },
                    {
                        "id": 410,
                        "id_analiza": 5,
                        "name": "Średnia zadań, klasa B płeć",
                        "series": {},
                        "options": {
                            chart: {
                                type: 'bar'
                            },
                            xAxis: {
                                type: 'category'
                            },
                        },
                        "labels": {
                            "1": "1",
                            "2": "2",
                            "3": "3",
                            "4": "4",
                            "5": "5",
                            "6": "6",
                            "7": "7",
                            "8": "8",
                            "9": "9",
                            "10": "10",
                            "11": "11",
                            "12": "12",
                            "13": "13",
                            "14.1": "14.1",
                            "14.2": "14.2",
                            "14.3": "14.3",
                            "14.4": "14.4",
                            "14.5": "14.5",
                            "14.6": "14.6"
                        },
                        "tags": {},
                        "opis": ""
                    },
                ]
            }
        }
    }
</script>
<style lang="stylus">
    @import url('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin-ext')
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons')
    @import '../../node_modules/vuetify/src/stylus/main.styl'
</style>