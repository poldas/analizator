<template>
    <div :id="item.name">
    </div>
</template>
<script type="text/babel">
    export default {
        props: ['item'],
        computed: {
            seriesData() {
                let keys = Object.keys(this.item.series)
                let series = keys.map((i) => {
                    return this.item.series[i]
                })
//        console.log(series)
//        if(!series) return []
                return series
            },
            options() {
                return {
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
                    series: this.seriesData
                }
            }
        },
        data () {
            return {
                itemValue: '',
            }
        },
        components: {
        }
    }
</script>
