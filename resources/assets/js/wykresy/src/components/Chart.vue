<template>
    <div class="row">
        <vue-highcharts :options="options" ref="lineCharts"></vue-highcharts>
        <button @click="load">load</button>
    </div>
</template>
<script>
    import VueHighcharts from 'vue2-highcharts'
    const asyncData = [
        {
            "data": [
                [
                    "1",
                    0.384615
                ],
                [
                    "2",
                    0.846154
                ],
                [
                    "3",
                    0.769231
                ],
                [
                    "4",
                    0.923077
                ],
                [
                    "5",
                    0.846154
                ],
                [
                    "6",
                    0.307692
                ],
                [
                    "7",
                    0.692308
                ],
                [
                    "8",
                    0
                ],
                [
                    "9",
                    0.384615
                ],
                [
                    "10",
                    0.769231
                ],
                [
                    "11",
                    0.615385
                ],
                [
                    "12",
                    0.076923
                ],
                [
                    "13",
                    0.538462
                ],
                [
                    "14",
                    0.461538
                ],
                [
                    "15",
                    0.615385
                ],
                [
                    "16",
                    0.923077
                ],
                [
                    "17",
                    0.846154
                ],
                [
                    "18",
                    0.307692
                ],
                [
                    "19",
                    0.923077
                ],
                [
                    "20",
                    0.846154
                ],
                [
                    "21",
                    0.153846
                ],
                [
                    "22.1",
                    0.461538
                ],
                [
                    "22.2",
                    0.846154
                ],
                [
                    "22.3",
                    0.692308
                ],
                [
                    "22.4",
                    0.269231
                ],
                [
                    "22.5",
                    0.230769
                ],
                [
                    "22.6",
                    0.153846
                ]
            ],
            "name": "mężczyzna"
        },
        {
            "data": [
                [
                    "1",
                    0.454545
                ],
                [
                    "2",
                    0.636364
                ],
                [
                    "3",
                    0.636364
                ],
                [
                    "4",
                    0.818182
                ],
                [
                    "5",
                    1
                ],
                [
                    "6",
                    0.454545
                ],
                [
                    "7",
                    0.181818
                ],
                [
                    "8",
                    0.181818
                ],
                [
                    "9",
                    0.090909
                ],
                [
                    "10",
                    0.818182
                ],
                [
                    "11",
                    0.909091
                ],
                [
                    "12",
                    0.363636
                ],
                [
                    "13",
                    0.636364
                ],
                [
                    "14",
                    0.636364
                ],
                [
                    "15",
                    0.818182
                ],
                [
                    "16",
                    0.909091
                ],
                [
                    "17",
                    0.909091
                ],
                [
                    "18",
                    0.363636
                ],
                [
                    "19",
                    0.909091
                ],
                [
                    "20",
                    0.545455
                ],
                [
                    "21",
                    0.136364
                ],
                [
                    "22.1",
                    0.613636
                ],
                [
                    "22.2",
                    1
                ],
                [
                    "22.3",
                    0.909091
                ],
                [
                    "22.4",
                    0.272727
                ],
                [
                    "22.5",
                    0.636364
                ],
                [
                    "22.6",
                    0.363636
                ]
            ],
            "name": "kobieta"
        }
    ]
    export default {
        components: {
            VueHighcharts
        },
        data(){
            return {
                options: {
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
                    },
                    series: [
                        {
                            "data": [
                                [
                                    "2.10",
                                    0.772727
                                ]
                            ],
                            "name": "miasto"
                        },
                        {
                            "data": [
                                [
                                    "2.10",
                                    0.5
                                ]
                            ],
                            "name": "wieś"
                        },
                        {
                            "data": [
                                [
                                    "2.4",
                                    0.636364
                                ]
                            ],
                            "name": "miasto"
                        },
                        {
                            "data": [
                                [
                                    "2.4",
                                    0.4
                                ]
                            ],
                            "name": "wieś"
                        },
                        {
                            "data": [
                                [
                                    "cały obszar IV",
                                    0.727273
                                ]
                            ],
                            "name": "miasto"
                        },
                        {
                            "data": [
                                [
                                    "cały obszar IV",
                                    0.466667
                                ]
                            ],
                            "name": "wieś"
                        }
                    ]
                }
            }
        },
        methods: {
            load()
            {
                let lineCharts = this.$refs.lineCharts;
                console.log(this.$refs.lineCharts.chart)
                lineCharts.delegateMethod('showLoading', 'Loading...');
                setTimeout(() => {
                    asyncData.forEach( i => lineCharts.addSeries(i, false))
//                    lineCharts.delegateMethod("update", asyncData);
                    lineCharts.delegateMethod("redraw");
                    lineCharts.delegateMethod("hideLoading");
                }, 2000)
            }
        }
    }
</script>