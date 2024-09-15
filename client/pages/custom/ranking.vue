<template>
  <main class="container" style="text-align: center; margin-top: 10px;">
    <div class="select-container">
      <select v-model="selectedSeason" name="season" class="select-box" @change="fetchRecords()">
        <option v-for="(season, index) in seasons" :key="index" :value="season">
          {{ season }}
        </option>
      </select>
      <select v-model="selectedArea" name="area" class="select-box" @change="fetchRecords()">
        <option v-for="(area, index) in areas" :key="index" :value="area.toLowerCase()">
          {{ area }}
        </option>
      </select>
    </div>

    <RakingTable :records="records" />

    <div class="paging_area">
      <template v-if="paging.isPrev">
        <a class="move_btn" href="javascript:;" @click="fetchRecords(paging.prevPage)">« 이전</a>
        <a class="pagenum" href="javascript:;" @click="fetchRecords(1)">1</a> ...
      </template>
      <template v-else>
        <span class="move_btn disabled">« 이전</span>
      </template>

      <a v-for="(page, index) in paging.pages" :key="index" class="pagenum" :class="{ current: page == currentPage }" href="javascript:;"
         @click="fetchRecords(page)"
      >
        {{ page }}
      </a>

      <template v-if="paging.isNext">
        ... <a class="pagenum" href="javascript:;" @click="fetchRecords(paging.pageCount)">
          {{ paging.pageCount }}
        </a>
        <a class="move_btn" href="javascript:;" @click="fetchRecords(paging.nextPage)">다음 »</a>
      </template>
      <template v-else>
        <span class="move_btn disabled">다음 »</span>
      </template>
    </div>
    <Adsense ins-style="display:inline-block; max-width:970px; width:100%; height:280px;"
             style="margin-top:100px; width:100%;" data-ad-client="ca-pub-8074145776836446" data-ad-slot="5113379318"
    />
    <LoadingIcon :is-processing="isProcessing" />
  </main>
</template>

<script>
import RakingTable from '../../components/RakingTable.vue'
import LoadingIcon from '../../components/LoadingIcon.vue'
import Toast from '~/plugins/toast.js'

export default {
  components: {
    RakingTable,
    LoadingIcon
  },
  layout: 'front_gray',
  data () {
    return {
      isProcessing: false,
      selectedSeason: '2024 Season 2',
      selectedArea: 'kr',
      seasons: [
        'Season 6',
        'Season 7',
        'Season 8',
        'Season 9',
        'Season 10',
        'Season 11',
        '2023 Season 1',
        '2023 Season 2',
        '2024 Season 1',
        '2024 Season 2'
      ],
      areas: [
        'USW',
        'USE',
        'EU',
        'KR',
        'AS'
      ],
      records: [],
      links: [],
      totalPages: [],
      pagination: [],
      currentPage: 0,
      paging: {
        isPrev: false,
        pages: [],
        isNext: false,
        prevPage: 0,
        nextPage: 0,
        pageCount: 0
      }
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
  },
  async created () {
    document.body.style.backgroundColor = '#ebebeb'
    this.selectedArea = this.$route.query.area ?? this.selectedArea
    this.selectedSeason = this.$route.query.season ?? this.selectedSeason
    const page = this.$route.query.page
    await this.fetchRecords(page)
  },
  methods: {
    async fetchRecords (page = 1) {
      this.isProcessing = true
      this.currentPage = page
      const query = { page, area: this.selectedArea, season: this.selectedSeason }
      const queryString = this.convertQuery(query)
      try {
        const response = await this.$http.get(`/records?${queryString}`)
        this.records = response.data.data
        this.paging = this.calcPaging(response.data)
        this.$router.push({ name: 'custom.ranking', query }).catch(() => { })
      } catch (err) {
        Toast.send('데이터를 찾을 수 없습니다.', 'warning')
        this.$router.push({ name: 'custom.index' })
      } finally {
        this.isProcessing = false
      }
    },
    convertQuery (obj) {
      return Object.keys(obj)
        .map(key => `${key}=${encodeURIComponent(obj[key])}`)
        .join('&')
    },
    calcPaging (obj) {
      const MORE_PAGE = 3
      const page = obj.current_page
      const pageCount = obj.last_page
      const startPage = Math.max(page - MORE_PAGE, 1)
      const endPage = Math.min(page + MORE_PAGE, pageCount)
      const prevPage = Math.max(startPage - MORE_PAGE - 1, 1)
      const nextPage = Math.min(endPage + MORE_PAGE + 1, pageCount)
      const pages = []
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i)
      }
      return {
        isPrev: startPage > 1,
        pages,
        isNext: endPage < pageCount,
        prevPage,
        nextPage,
        pageCount
      }
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

.pagenum {
    display: inline-block;
    border: 1px solid transparent;
    color: gray;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    padding: 6px;
}

.pagenum:hover {
    color: orange;
    border: 1px solid orange;
}

.pagenum.current {
    color: orange;
    text-decoration: underline;
}

.move_btn {
    color: gray;
}

.disabled {
    color: silver;
}

.paging_area {
    text-align: center;
    margin-bottom: 10px;
}

.select-container {
    display: block;
    text-align: right;
}

.select-box {
    background-color: #fff;
}
</style>
