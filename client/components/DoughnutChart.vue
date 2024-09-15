<template>
  <Doughnut
    :chart-options="chartOptions"
    :chart-data="chartData"
    :chart-id="chartId"
    :dataset-id-key="datasetIdKey"
    :plugins="plugins"
    :css-classes="cssClasses"
    :styles="styles"
    :width="width"
    :height="height"
  />
</template>

<script>
import { Doughnut } from 'vue-chartjs/legacy'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale
} from 'chart.js'
import ChartDataLabels from 'chartjs-plugin-datalabels' // https://chartjs-plugin-datalabels.netlify.app/guide/options.html#scriptable-options
import Toast from '~/plugins/toast.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, ChartDataLabels)

export default {
  name: 'DoughnutChart',
  components: {
    Doughnut
  },
  props: {
    chartId: {
      type: String,
      default: 'doughnut-chart'
    },
    datasetIdKey: {
      type: String,
      default: 'label'
    },
    width: {
      type: Number,
      default: 400
    },
    height: {
      type: Number,
      default: 400
    },
    cssClasses: {
      type: String,
      default: ''
    },
    styles: {
      type: Object,
      default: () => ({})
    },
    plugins: {
      type: Array,
      default: () => [ChartDataLabels]
    }
  },
  data () {
    return {
      chartData: {
        labels: ['Protoss', 'Zerg', 'Terran', '-'],
        datasets: [
          {
            backgroundColor: ['#ddd055', '#be73e4', '#80d4ff', '#ccc'],
            borderWidth: [0, 0, 0, 0],
            data: [40, 20, 80, 10]
          }
        ]
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 1,
        plugins: {
          datalabels: {
            formatter: (value, context) => {
              const sum = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0)
              const percentage = ((value / sum) * 100).toFixed(2) + '%'
              return percentage === '0.00%' ? '' : percentage
            },
            color: '#111',
            anchor: 'end',
            align: 'start',
            offset: 15,
            font: {
              size: 14
            }
          }
        }
      }
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
  },
  async created () {
    await this.loadData()
  },
  methods: {
    async loadData () {
      try {
        const { data } = await this.$http.get('/statistics/numKind')
        const newData = [0, 0, 0, 0]
        data.forEach((item) => {
          switch (item.kind) {
            case 'P':
              newData[0] = item.cnt
              break
            case 'Z':
              newData[1] = item.cnt
              break
            case 'T':
              newData[2] = item.cnt
              break
            case '-':
              newData[3] = item.cnt
              break
          }
        })
        this.chartData.datasets[0].data = newData
      } catch (err) {
        console.error(err)
        Toast.send('전송에 실패했습니다.', 'warning')
      }
    }
  }
}
</script>
