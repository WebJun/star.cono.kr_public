<template>
  <div>
    <LineChartGenerator :chart-options="chartOptions" :chart-data="chartData" :chart-id="chartId"
                        :dataset-id-key="datasetIdKey" :plugins="plugins" :css-classes="cssClasses" :styles="styles" :width="width"
                        :height="height"
    />
  </div>
</template>

<script>
import { Line as LineChartGenerator } from 'vue-chartjs/legacy'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  CategoryScale,
  PointElement
} from 'chart.js'
import Toast from '~/plugins/toast.js'

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  CategoryScale,
  PointElement
)

export default {
  name: 'LineChart',
  components: {
    LineChartGenerator
  },
  props: {
    chartId: {
      type: String,
      default: 'line-chart'
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
      default: '',
      type: String
    },
    styles: {
      type: Object,
      default: () => { }
    },
    plugins: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      chartData: {
        datasets: [],
        labels: []
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: 1,
        aspectRatio: 1,
        tooltips: {
          mode: 'index',
          intersect: false
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          xAxes: {
            title: {
              display: true,
              text: '← 초보자　　　　숙련도　　　　숙련자 →'
            }
          },
          yAxes: {
            beginAtZero: true,
            title: {
              display: true,
              text: '종족 빈도율(%)'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: '등급에 따른 종족 빈도 그래프'
          },
          datalabels: {
            display: false // 데이터 라벨 비활성화
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
        const res = await this.$http.get('/statistics/ability-kind-ajax')
        const data = res.data

        this.chartData = {
          datasets: [
            this.createDataset('Protoss', 'P', '#ddd055', '#ddd055', data),
            this.createDataset('Zerg', 'Z', '#be73e4', '#be73e4', data),
            this.createDataset('Terran', 'T', '#80d4ff', '#80d4ff', data)
          ],
          labels: this.getLabelsData(data)
        }
      } catch (err) {
        Toast.send('전송에 실패했습니다.', 'warning')
      }
    },
    createDataset (label, dataKey, backgroundColor, borderColor, data) {
      return {
        label,
        fill: false,
        backgroundColor,
        borderColor,
        data: data[dataKey]
      }
    },
    getLabelsData (data) {
      const labelsCount = data.P.length
      const labels = []
      const rate = 100 / labelsCount
      for (let i = 0; i < labelsCount; i++) {
        labels.push(100 - Math.round(i * rate))
      }
      return labels
    }
  }
}
</script>
