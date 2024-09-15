<template>
  <main class="container">
    <div class="logo">
      <router-link :to="{ name: 'custom.index' }" class="logo-link">
        STAR
      </router-link>
    </div>

    <div class="search-container">
      <input v-model="searchToon" type="text" name="id" class="search-input" placeholder="ID" @keyup="search">
      <select v-model="selectedArea" class="language-select" @change="search()">
        <option v-for="(area, index) in areas" :key="index" :value="area.toLowerCase()">
          {{ area }}
        </option>
      </select>
      <button type="button" class="search-button" @click="searchBtn">
        검색
      </button>
    </div>
    <div id="search_result">
      <div v-for="(record, index) in records" :key="index" class="record" @click="moveSearchPage(record.area, record.toon)">
        <img src="/storage/images/default.png" class="icon">
        <div class="text_wrap">
          <div class="id">
            {{ record.toon }}
          </div>
          <div class="mmr">
            {{ getGrade(record.bucket) }} - {{ record.rating }}
          </div>
        </div>
      </div>
    </div>
    <Adsense ins-style="display:inline-block; max-width:970px; width:100%; height:280px;"
             style="margin-top:100px; width:100%;" data-ad-client="ca-pub-8074145776836446" data-ad-slot="5113379318"
    />
  </main>
</template>

<script>
import Toast from '~/plugins/toast.js'

export default {
  layout: 'front_blue',
  data () {
    return {
      searchToon: '',
      records: [],
      selectedArea: 'kr',
      areas: [
        'USW',
        'USE',
        'EU',
        'KR',
        'AS'
      ]
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
    document.body.style.backgroundColor = '#5383e9'
  },
  methods: {
    async search () {
      if (this.searchToon.length === 0) {
        return
      }
      const area = this.selectedArea.toLowerCase()
      const response = await this.$http.get(`/records/search/${area}/${this.searchToon}`)
      this.records = response.data
    },
    moveSearchPage (area, toon) {
      const params = {
        area: this.selectedArea.toLowerCase(),
        toon: encodeURIComponent(toon)
      }
      this.$router.push({ name: 'custom.search', params })
    },
    getGrade (bucket) {
      const grades = ['-', 'F', 'E', 'D', 'C', 'B', 'A', 'S']
      return grades[bucket]
    },
    searchBtn () {
      Toast.send('2025년 내에 수정 예정입니다.', 'warning')
    }
  }
}
</script>

<style scoped>
main {
    flex: 1 0 auto;
}

div {
    display: inline-block;
}

.container {
    text-align: center;
}

.logo {
    display: block;
    color: #fff;
    font-size: 100px;
    text-shadow: 4px 6px 2px #000;
    font-weight: 900;
    padding: 30px 0;
}

@media (max-width: 992px) {
    .logo {
        font-size: 70px !important;
    }
}

.logo-link {
    color: #fff;
    text-decoration: none;
}

.search-container {
    display: inline-block;
    position: relative;
    margin-top: 3px;
    width: 100%;
    max-width: 700px;
}

.search-input {
    border: 0;
    width: 100%;
    height: 50px;
    padding: 0 0 0 10px;
    font-size: 14px;
}

.language-select {
    position: absolute;
    top: 10px;
    right: 50px;
    height: 30px;
    width: 40px;
    background-color: gray;
    color: #fff;
    border: 0;
}

.search-button {
    position: absolute;
    top: 10px;
    right: 5px;
    background-color: #00b2ff;
    height: 30px;
    width: 40px;
    color: #fff;
    border: 0;
}

#search_result {
    display: inline-block;
    width: 100%;
    max-width: 700px;
    background-color: #fff;
    text-align: left;
    margin-bottom: 30px;
}

#search_result .record {
    display: block;
    padding: 0 0 0 17px;
    height: 50px;
    line-height: 50px;
    cursor: pointer;
}

#search_result .record .text_wrap {
    vertical-align: middle;
    margin: 0 0 0 5px;
}

#search_result .record .text_wrap .id {
    display: block;
    line-height: 15px;
    font-size: 14px;
    color: #d53f3f;
}

#search_result .record .text_wrap .mmr {
    display: block;
    line-height: 15px;
    color: #666
}

#search_result .record .icon {
    width: 34px;
    height: 34px;
}

#search_result .record:hover {
    background-color: #ccc
}
</style>
