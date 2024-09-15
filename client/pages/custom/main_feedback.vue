<template>
  <main class="container">
    <div id="feedback_form">
      <h1 class="screen_out">
        문의/피드백
      </h1>

      <div class="form-group">
        <label for="email">이메일주소</label>
        <input id="email" v-model="send.email" type="email" class="inp">
      </div>

      <div class="form-group">
        <label for="phone">휴대폰번호</label>
        <input id="phone" v-model="send.phone" type="tel" class="inp">
      </div>

      <div class="form-group">
        <label for="content">내용</label>
        <textarea id="content" v-model="send.content" class="inp" rows="10" style="height:auto;" />
      </div>

      <div class="privacy-info">
        <div>개인정보 수집.이용에 대한 안내</div>
        <div>필수 수집·이용 항목 (문의접수와 처리,회신을 위한 최소한의 개인정보입니다. 동의가 필요합니다.)</div>
      </div>

      <table class="feedback">
        <tr>
          <th>수집항목</th>
          <th>목적</th>
          <th>보유기간</th>
        </tr>
        <tr>
          <td>이메일 주소, 휴대폰 번호</td>
          <td>고객문의 및 상담요청에 대한 회신,<br>상담을 위한 서비스 이용기록 조회</td>
          <td>문의 접수 후 3년간 보관</td>
        </tr>
      </table>

      <div class="privacy-link">
        더 자세한 내용에 대해서는 <a href="/main/privacy" target="_blank">개인정보처리방침</a>을 참고하시기 바랍니다.
      </div>

      <div class="agree-checkbox">
        <label style="display:flex; justify-content: center;"><input v-model="send.agree" type="checkbox"> 위 내용에
          동의합니다.</label>
      </div>

      <button type="button" class="submit-button" @click="addFeedback()">
        문의접수
      </button>
    </div>
    <LoadingIcon :is-processing="isProcessing" />
  </main>
</template>

<script>
import LoadingIcon from '../../components/LoadingIcon.vue'
import Toast from '~/plugins/toast.js'

export default {
  components: {
    LoadingIcon
  },
  layout: 'front_blue',
  data () {
    return {
      isProcessing: false,
      send: {
        email: '',
        phone: '',
        content: '',
        agree: false
      }
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
    document.body.style.backgroundColor = '#5383e9'
  },
  created () {

  },
  methods: {
    async addFeedback () {
      if (this.isValidEmail(this.send.email) === false) {
        Toast.send('이메일을 다시 입력해주세요.', 'warning')
        return
      }
      if (this.send.phone.length <= 10) {
        Toast.send('휴대폰번호를 다시 입력해주세요.', 'warning')
        return
      }
      if (this.send.content.length === 0) {
        Toast.send('내용을 다시 입력해주세요.', 'warning')
        return
      }
      if (this.send.agree === false) {
        Toast.send('개인정보 수집이용에 동의해야합니다.', 'warning')
        return
      }
      this.isProcessing = true
      const formData = new FormData()
      formData.append('email', this.send.email)
      formData.append('phone', this.send.phone)
      formData.append('content', this.send.content)
      try {
        await this.$http.post('/feedbacks', formData)
        Toast.send('문의/피드백 주셔서 감사합니다.')
        setTimeout(() => {
          this.$router.push({ name: 'custom.index' })
        }, 3000)
      } catch (err) {
        Toast.send('전송에 실패했습니다.', 'warning')
      } finally {
        this.isProcessing = false
      }
    },
    isValidEmail (email) {
      // 이메일 주소의 유효성을 검증하는 정규표현식
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
      return emailPattern.test(email)
    }
  }
}
</script>

<style scoped>
main a {
    color: #0500d1;
}

.container {
    margin-top: 30px;
    background-color: #fff;
}

#feedback_form {
    max-width: 500px;
}

.form-group {
    margin-top: 10px;
}

.inp {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

label {
    font-weight: bold;
}

.feedback {
    margin-top: 10px;
    width: 100%;
    border-collapse: collapse;
}

.feedback th,
.feedback td {
    border: 1px solid #ccc;
    padding: 8px;
}

.privacy-info {
    margin-top: 10px;
    font-weight: bold;
}

.privacy-link {
    margin-top: 10px;
}

.agree-checkbox {
    margin-top: 10px;
}

.submit-button {
    background-color: #F4d201;
    width: 186px;
    height: 42px;
    margin: 10px 0 30px 150px;
    font-weight: bold;
    border: 0;
    font-size: 16px;
}
</style>
