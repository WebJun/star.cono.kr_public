<template>
  <div id="reply">
    <div style="text-align:left; margin-bottom:5px;">
      총 <span id="reply_cnt">{{ total }}</span>개의 댓글이 있습니다.
    </div>
    <ul id="reply_lists">
      <li v-for="(reply, index) in replies" :key="index" class="clearfix">
        <div class="nickname one">
          {{ reply.nickname }}
        </div>
        <div class="content one">
          {{ reply.content }}
        </div>
        <div class="datetime one">
          {{ getDatetime(reply.created_at) }}
        </div>
        <!-- <div class="delete one" @click="showDeletePassword(reply.id)">x</div> -->
        <div class="delete one" @click="deleteReply(reply.id)">
          x
        </div>
      </li>
    </ul>
    <form id="reply_form">
      <table id="write">
        <tbody>
          <tr>
            <td>
              <div class="test">
                <input v-model="send.nickname" type="text" class="inp" maxlength="8"
                       :class="{ jsinp: active.nickname }" @focusout="activeInput()"
                >
                <div class="placeholders" :class="{ jsplaceholders: active.nickname }">
                  닉네임
                </div>
                <div data-lastpass-icon-root="true"
                     style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"
                />
              </div>
            </td>
            <td>
              <div class="test">
                <input v-model="send.password" type="password" class="inp" maxlength="6"
                       :class="{ jsinp: active.password }" @focusout="activeInput()"
                >
                <div class="placeholders" :class="{ jsplaceholders: active.password }">
                  비밀번호
                </div>
                <div data-lastpass-icon-root="true"
                     style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"
                />
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:10px 0 0 0;">
              <div class="test">
                <textarea v-model="send.content" type="text" class="inp" style="width:100%;" rows="2"
                          :class="{ jsinp: active.content }" @focusout="activeInput()"
                />
                <div class="placeholders" :class="{ jsplaceholders: active.content }">
                  내용
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:right;">
              <button type="button" class="btn btn-primary btn-sm" style="font-size:12px; padding:5px; "
                      @click="addReply()"
              >
                등록
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>

  <!-- <div id='delete_tooltip' style="">
            <div style='position:relative; width:160px; '>
                <div class='test' style='width:calc(100% - 45px); margin-right:45px;'>
                    <input type='password' name='delete_password' class='inp' maxlength='20' required>
                    <div class='placeholders'>비밀번호</div>
                </div>
                <button type='button' id='delete_btn' class='btn btn-danger btn-sm'
                    style='position:absolute; top:0; right:0;'>삭제</button>
            </div>
        </div> -->
</template>

<script>
import Toast from '~/plugins/toast.js'

export default {
  data () {
    return {
      replies: [],
      send: {
        nickname: '',
        password: '',
        content: ''
      },
      area: this.$route.params.area,
      toon: this.$route.params.toon,
      active: {
        nickname: false,
        password: false,
        content: false
      },
      total: 0
    }
  },
  mounted () {
    Toast.setBvToast(this.$bvToast)
  },
  async created () {
    await this.fetchReplys()
  },
  methods: {
    async fetchReplys () {
      const response = await this.$http.get(`/replies/${this.area}/${this.toon}`)
      this.replies = response.data.data
      this.total = response.data.total
    },
    async addReply () {
      if (this.send.nickname.length === 0) {
        Toast.send('닉네임을 다시 입력해주세요.', 'warning')
        return
      }
      if (this.send.password.length < 4) {
        Toast.send('비밀번호는 4글자 이상이어야 합니다.', 'warning')
        return
      }
      if (this.send.content.length === 0) {
        Toast.send('내용을 다시 입력해주세요.', 'warning')
        return
      }
      const formData = new FormData()
      formData.append('area', this.area)
      formData.append('toon', this.toon)
      formData.append('nickname', this.send.nickname)
      formData.append('password', this.send.password)
      formData.append('content', this.send.content)
      await this.$http.post('/replies', formData)
        .then((res) => {
          this.resetInput()
          this.activeInput()
          this.fetchReplys()
        })
        .catch(() => {
          Toast.send('전송에 실패했습니다.', 'warning')
        })
    },
    resetInput () {
      this.send = {
        nickname: '',
        password: '',
        content: ''
      }
    },
    showDeletePassword (id) {
      console.log(id)
    },
    async deleteReply (id) {
      const password = prompt('비밀번호를 입력하세요.')
      if (password.length < 4) {
        Toast.send('비밀번호는 4글자 이상이어야 합니다.', 'warning')
        return
      }
      const formData = new FormData()
      formData.append('password', password)
      await this.$http.post(`/replies/${id}`, formData)
        .then((res) => {
          Toast.send('댓글을 삭제했습니다.', 'warning')
          this.fetchReplys()
        })
        .catch((err) => {
          if (err.response && err.response.status === 403) {
            Toast.send('비밀번호가 틀렸습니다.', 'warning')
            return
          }
          Toast.send('전송에 실패했습니다.', 'warning')
        })
    },
    getDatetime (str) {
      let d = new Date()
      if (typeof str !== 'undefined') {
        d = new Date(str)
      }
      const s =
                String(d.getFullYear()).padStart(4, '0') +
                '-' +
                String(d.getMonth() + 1).padStart(2, '0') +
                '-' +
                String(d.getDate()).padStart(2, '0') +
                ' ' +
                String(d.getHours()).padStart(2, '0') +
                ':' +
                String(d.getMinutes()).padStart(2, '0') +
                ':' +
                String(d.getSeconds()).padStart(2, '0')
      return s
    },
    activeInput () {
      if (this.send.nickname !== '') {
        this.active.nickname = true
      } else {
        this.active.nickname = false
      }
      if (this.send.password !== '') {
        this.active.password = true
      } else {
        this.active.password = false
      }
      if (this.send.content !== '') {
        this.active.content = true
      } else {
        this.active.content = false
      }
    }
  }
}
</script>

<style scoped>
#reply {
    width: 100%;
    background-color: #fff;
    border-radius: 5px;
    border: 1px solid #ddd;
    text-align: left;
    padding: 10px;
}

#reply_cnt {
    font-weight: bold;
}

#write {
    width: 100%;
}

#reply_lists {
    padding: 0;
    margin: 0 0 10px 0;
}

#reply_lists li {
    position: relative;
    list-style-type: none;
    padding: 10px 0 0 0;
    margin: 0 0 10px 0;
    border-top: 1px solid #eee;
}

@media (max-width: 992px) {
    #reply_lists li div {
        display: block;
        text-align: left;
        color: #333;
    }

    #reply_lists .nickname {
        color: #333;
        font-weight: bold
    }

    #reply_lists .datetime {
        color: #999;
    }

    #reply_lists .delete {
        width: 20px;
        cursor: pointer;
        position: absolute;
        top: 2px;
        right: 0px;
        color: #aaa;
        text-align: center;
    }
}

@media (min-width: 992px) {
    #reply_lists .nickname {
        float: left;
        width: 100px;
        text-align: left !important;
        font-weight: bold;
    }

    #reply_lists .content {
        float: left;
        width: calc(100% - 240px);
        text-align: left !important;
    }

    #reply_lists .datetime {
        float: left;
        width: 120px;
        color: #999;
    }

    #reply_lists .delete {
        float: left;
        width: 20px;
        color: #aaa;
        cursor: pointer;
        margin: -1px 0 0 0;
    }

    #reply_lists .clearfix {
        zoom: 1;
        /* ie 6,7 */
    }

    #reply_lists .clearfix:before,
    .clearfix:after {
        content: ' ';
        display: table;
    }

    #reply_lists .clearfix:after {
        clear: both;
    }

    #reply_lists .one {
        float: left;
        text-align: center;
    }
}

.test {
    position: relative;
    width: 100%;
}

.inp {
    border: 1px solid #ccc;
    padding: 5px 12px;
    font-size: 14px;
    width: 100%;
    border-radius: 4px;
    outline: 0;
}

.placeholders {
    position: absolute;
    top: 6px;
    left: 8px;
    pointer-events: none;
    background-color: #fff;
    padding: 0 3px;
    color: #888;
    font-size: 12px;
    transition: all .4s;
}

.inp:focus {
    border: 2px solid #1a73e8 !important;
}

.inp:focus+.placeholders {
    top: -8px;
    font-size: 12px;
    color: #1a73e8;
    transition: all .4s;
}

.jsinp {
    border: 2px solid #1a73e8 !important;
}

.jsplaceholders {
    top: -8px;
    font-size: 12px;
    color: #1a73e8;
}
</style>
