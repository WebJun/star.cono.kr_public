<template>
  <main class="container" style="text-align: center; margin-top: 10px;">
    <div class="select-container">
      <select class="select-box" disabled>
        <option :value="$route.params.area">
          {{ $route.params.area.toUpperCase() }}
        </option>
      </select>
    </div>

    <div id="lists" class="table-responsive">
      <table class="table">
        <thead class="table-header">
          <tr class="title">
            <td class="season">
              시즌
            </td>
            <td class="seq">
              순위
            </td>
            <td class="id">
              아이디
            </td>
            <td class="grade">
              등급
            </td>
            <td class="mmr">
              MMR
            </td>
            <td class="kind">
              종족
            </td>
            <td class="winning_rate">
              승률
            </td>
          </tr>
        </thead>
        <tbody class="table-body">
          <tr v-for="(record, index) in records" :key="index" alt="move" :title="getTitle(record.created_at)" :data-area="record.area"
              :data-id="record.toon"
          >
            <td>{{ record.season }}</td>
            <td>{{ record.rank }}</td>
            <td class="ids-wrap">
              <div class="ids">
                {{ record.toon }}
              </div>
              <div class="nickname">
                {{ record.battletag }}
              </div>
            </td>
            <td>{{ getGrade(record.bucket) }}</td>
            <td>{{ record.rating }}</td>
            <td>
              <div class="kind_m">
                {{ getKindMobile(record.feature_stat) }}
              </div>
              <div class="kind_pc">
                {{ getKindPc(record.feature_stat) }}
              </div>
            </td>
            <td>
              <BarGraph :wins="record.wins" :losses="record.losses" class="bar-graph" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Reply />
  </main>
</template>

<script>
import Reply from '../../components/Reply.vue'
import BarGraph from '../../components/BarGraph.vue'
import Toast from '~/plugins/toast.js'

export default {
  components: {
    BarGraph,
    Reply
  },
  layout: 'front_gray',
  data () {
    return {
      records: [],
      area: this.$route.params.area,
      toon: this.$route.params.toon
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
  },
  async created () {
    document.body.style.backgroundColor = '#ebebeb'
    await this.fetchRecords()
  },
  methods: {
    async fetchRecords () {
      try {
        const response = await this.$http.get(`/records/${this.area}/${this.toon}`)
        this.records = this.convertRecords(response.data.data)
        if (this.records.length === 0) {
          throw new Error('데이터를 찾을 수 없습니다.')
        }
      } catch (err) {
        Toast.send(err.message, 'warning')
        this.$router.push({ name: 'custom.index' })
      }
    },
    convertRecords (records) {
      for (const record of records) {
        const total = record.wins + record.losses
        const winningRate = Math.round((record.wins * 100) / total)
        record.winning_rate = winningRate
        record.losing_rate = 100 - winningRate
      }
      return records
    },
    getGrade (bucket) {
      const grades = ['-', 'F', 'E', 'D', 'C', 'B', 'A', 'S']
      return grades[bucket]
    },
    getKindPc (featureStat) {
      return featureStat.charAt(0).toUpperCase() + featureStat.slice(1)
    },
    getKindMobile (featureStat) {
      return featureStat.charAt(0).toUpperCase()
    },
    getTitle (time) {
      return `${this.calculateElapsedTime(time)} 전 업데이트 되었습니다.`
    },
    calculateElapsedTime (targetDatetime) {
      const currentTimestamp = new Date().getTime() // 밀리초 단위
      const targetTimestamp = new Date(targetDatetime).getTime()
      const timeDifferenceInSeconds = (currentTimestamp - targetTimestamp) / 1000

      let timeDiff = Math.ceil(timeDifferenceInSeconds / 60)
      if (timeDiff < 60) {
        return `${timeDiff}분`
      }
      timeDiff = Math.ceil(timeDiff / 60)
      if (timeDiff < 24) {
        return `${timeDiff}시간`
      }
      timeDiff = Math.ceil(timeDiff / 24)
      return `${timeDiff}일`
    }
  }
}
</script>

<style scoped>
.container {
    flex: 1 0 auto;
}

a {
    color: #fff;
    text-decoration: none;
}

#lists {
    margin-top: 10px;
}

.table-header {
    background-color: #f1f1f1;
}

.table {
    text-align: center;
}

.table td {
    padding: 15px 5px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #ccc;
}

.table-header .season {
    width: 15%;
}

.table-header .rank {
    width: 10%;
}

.table-header .toon {
    width: 15%;
}

.table-header .grade {
    width: 10%;
}

.table-header .mmr {
    width: 15%;
}

.table-header .kind {
    width: 10%;
}

.table-header .winning_rate {
    width: 25%;
}

@media (max-width: 992px) {
    .table td {
        padding: 10px 0px;
        vertical-align: middle;
    }

    .table td .graph_wrap {
        width: 100px !important;
    }

    .table td .graph_wrap .graph {
        width: 66px !important;
    }

    .table .thead .title .grade {
        width: 24px !important;
    }

    .table .thead .title .mmr {
        width: 40px !important;
    }

    .table .thead .title .kind {
        width: 24px !important;
    }
}

/* mobile */
@media (max-width: 992px) {
    .kind_pc {
        display: none;
    }

    .bar-graph {
        width: 100%;
    }
}

/* pc */
@media (min-width: 992px) {
    .kind_m {
        display: none;
    }

    .bar-graph {
        width: 60%;
    }
}

.table-body .ids-wrap {
    text-align: left;
}

.table-body .ids-wrap .ids {
    display: block;
    font-weight: bold;
}

.table-body .ids-wrap .nickname {
    display: block;
    font-size: 11px;
}

.select-container {
    display: block;
    text-align: right;
}

.select-box {
    background-color: #fff;
}
</style>
