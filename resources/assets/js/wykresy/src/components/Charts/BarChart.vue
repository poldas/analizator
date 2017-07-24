<script type="text/babel">
  import { Bar, mixins } from 'vue-chartjs'

  export default Bar.extend({
    props: ["chartData", "options"],
    mixins: [mixins.reactiveProp],
    mounted () {
      this.renderChart(this.chartData, this.options)
    }
  })
</script>
