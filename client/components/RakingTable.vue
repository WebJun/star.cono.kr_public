<template>
  <div>
    <div class="row">
      <div v-for="(record, index) in recordTop1" :key="index" class="col-lg-8 offset-lg-2 top1-rank-wrap"
           :title="getTitle(record.created_at)" @click="moveSearchPage(record.area, record.toon)"
      >
        <div class="high-rank">
          {{ record.rank }}
        </div>
        <div>
          <img class="top1-avatar" :src="convertLocalImage(record.avatar)" @error="handleAvatarImageError">
        </div>
        <div class="top1-text-wrap">
          <div class="top1-text">
            <div class="toon">
              {{ record.toon }}
            </div>
            <div>{{ record.battletag }} / {{ getKindMobile(record.feature_stat) }} / {{ record.rating }}</div>
            <div class="bar-graph-wrap">
              <BarGraph :wins="record.wins" :losses="record.losses" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div v-for="(record, index) in recordTop2to5" :key="index" class="col-lg-3 col-6 high_rank" style="margin-top:10px;">
        <div class="top2to5-rank-wrap" :title="getTitle(record.created_at)"
             @click="moveSearchPage(record.area, record.toon)"
        >
          <div class="high-rank">
            {{ record.rank }}
          </div>
          <div>
            <img class="top2to5-avatar" :src="convertLocalImage(record.avatar)" @error="handleAvatarImageError">
          </div>
          <div class="toon">
            {{ record.toon }}
          </div>
          <div>{{ record.battletag }} / {{ getKindMobile(record.feature_stat) }} / {{ record.rating }}</div>
          <div class="bar-graph-wrap">
            <BarGraph :wins="record.wins" :losses="record.losses" />
          </div>
        </div>
      </div>
    </div>
    <div id="lists" class="table-responsive">
      <table class="table">
        <thead class="table-header">
          <tr class="title">
            <td class="rank" />
            <td class="toon">
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
          <tr v-for="(record, index) in recordRest" :key="index" :title="getTitle(record.created_at)"
              @click="moveSearchPage(record.area, record.toon)"
          >
            <td>{{ record.rank }}</td>
            <td class="toon-wrap">
              <div class="toon">
                {{ record.toon }}
              </div>
              <div class="battletag">
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
  </div>
</template>

<script>
import BarGraph from './BarGraph.vue'

export default {
  components: {
    BarGraph
  },
  props: {
    records: Array
  },
  data () {
    return {
      imageBasePath: '/storage/images',
      defaultFilename: 'default.png'
    }
  },
  computed: {
    // 1등
    recordTop1 () {
      return this.records.filter(record => record.rank === 1)
    },
    // 2~5등
    recordTop2to5 () {
      return this.records.filter(record => record.rank >= 2 && record.rank <= 5)
    },
    // 1~5등 제외한 나머지
    recordRest () {
      return this.records.filter(record => record.rank > 5)
    }
  },
  methods: {
    getGrade (bucket) {
      const grades = ['-', 'F', 'E', 'D', 'C', 'B', 'A', 'S']
      return grades[bucket]
    },
    getKindPc (featureStat) {
      if (!featureStat) {
        return '-'
      }
      return featureStat.charAt(0).toUpperCase() + featureStat.slice(1)
    },
    getKindMobile (featureStat) {
      if (!featureStat) {
        return '-'
      }
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
    },
    moveSearchPage (area, toon) {
      this.$router.push({ name: 'custom.search', params: { area: area.toLowerCase(), toon } })
    },
    convertLocalImage (url) {
      let filename = this.defaultFilename
      if (url !== null) {
        const parts = url.split('/')
        filename = parts.pop()
      }
      return `${this.imageBasePath}/${filename}`
    },
    handleAvatarImageError (e) {
      e.target.src = ''
    }
  }
}
</script>

<style scoped>
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

.table tbody tr {
    cursor: pointer;
}

.table tbody tr:hover {
    background-color: #ccc;
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
    width: 40%;
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

    .high_rank:nth-child(2n-1) {
        padding: 0 5px 0 0;
    }

    .high_rank:nth-child(2n) {
        padding: 0 0 0 5px;
    }
}

.top2to5-avatar {
    background-color: #000;
    width: 50px;
    height: 50px;
}

/* pc */
@media (min-width: 992px) {
    .top1-text-wrap {
        display: flex;
        align-items: center;
        width: 280px;
        margin-left: 30px;
    }

    .top1-text {
        display: flex;
        flex-direction: column;
        width: 280px;
    }

    .top1-avatar {
        background-color: #000;
        width: 100px;
        height: 100px;
    }

    .kind_m {
        display: none;
    }

    .bar-graph-wrap {
        width: 100%;
    }

    .bar-graph {
        width: 60%;
    }
}

/* mobile */
@media (max-width: 992px) {
    .top1-text-wrap {
        display: flex;
        align-items: center;
        width: 140px;
        margin-left: 30px;
    }

    .top1-text {
        display: flex;
        flex-direction: column;
        width: 140px;
    }

    .top1-avatar {
        background-color: #000;
        width: 50px;
        height: 50px;
    }

    .kind_pc {
        display: none;
    }

    .bar-graph-wrap {
        width: 100%;
    }

    .bar-graph {
        width: 100%;
    }
}

.table-body .toon-wrap .toon {
    display: block;
    font-weight: bold;
}

.table-body .toon-wrap .battletag {
    display: block;
    font-size: 11px;
}

.top1-rank-wrap {
    display: flex;
    border: 5px solid #bbb;
    justify-content: center;
    background-color: #fff;
    padding: 20px 10px;
    cursor: pointer;
}

.high-rank {
    position: absolute;
    top: -3px;
    left: -3px;
    background-color: #bbb;
    width: 30px;
    height: 30px;
    color: #fff;
    font-size: 20px;
}

.row {
    padding: 5px;
}

.row .toon {
    font-size: 20px;
    font-weight: bold;
}

.top2to5-rank-wrap {
    position: relative;
    display: flex;
    border: 3px solid #bbb;
    flex-direction: column;
    background-color: #fff;
    padding: 20px;
    cursor: pointer;
}
</style>
