<template>
  <main class="container">
    <ol id="menu">
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/emptyTable')">
          record_temps 테이블 비우기
        </button>
      </li>
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/insertRecordOldSeasons')">
          예전 테이블 밀어넣기(record_old_seasons)
        </button>
      </li>
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/insertRecordNewSeasons')">
          신규 테이블 밀어넣기(record_new_seasons)
        </button>
      </li>
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/destroyUnusedData')">
          Frontier League, GB 소프트삭제
        </button>
      </li>
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/replaceTable')">
          테이블 교체하기 (records ↔ record_temps)
        </button>
      </li>
      <li>
        <button class="btn btn-outline-success" @click="sendApi('/recordTemps/downAvatarImage')">
          신규 아바타 이미지 다운로드
        </button>
      </li>
    </ol>
    <div id="loding-icon" :class="{ isProcessing: isProcessing }" class="spinner-border text-warning" />
    <ol>
      <li v-for="(log, index) in logs" :key="index">
        {{ log }}
      </li>
    </ol>
    <LoadingIcon :is-processing="isProcessing" />
  </main>
</template>

<script>
import LoadingIcon from '../../components/LoadingIcon.vue'

export default {
  name: 'custom.admin.main',
  components: {
    LoadingIcon
  },
  middleware: 'auth',
  data () {
    return {
      isProcessing: false,
      logs: []
    }
  },
  created () {
    document.body.style.backgroundColor = 'transparent'
  },
  methods: {
    async sendApi (url) {
      if (confirm(`${url} 정말?`) === false) {
        return
      }
      this.isProcessing = true
      const oldTime = new Date().getTime()
      try {
        const res = await this.$http.get(url)
        if (!res.data.message) {
          throw new Error('message가 없음')
        }
        const nowTime = new Date().getTime()
        const secGap = (nowTime - oldTime) / 1000
        this.logs.push(`[SUCCESS] ${url} - ${secGap}초 소요 - ${res.data.message}`)
      } catch (err) {
        const nowTime = new Date().getTime()
        const secGap = (nowTime - oldTime) / 1000
        this.logs.push(`[ERROR] ${url} - ${secGap}초 소요 - ${err}`)
      } finally {
        this.isProcessing = false
      }
    }
  }
}
</script>

<style scoped>
#menu li {
    margin-top: 5px;
}

#feedback {
    width: 1000px;
}

#feedback thead {
    font-weight: bold;
}

#feedback td {
    border: 1px solid #333
}

#loding-icon {
    display: none;
    position: absolute;
    left: 50%;
    top: 50%;
}

.isProcessing {
    display: inline-block !important;
}
</style>
