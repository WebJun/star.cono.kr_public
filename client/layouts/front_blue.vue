<template>
  <div class="body body-flex blue">
    <nav class="navbar navbar-expand-sm navbar-dark navbar-expand nav-custom">
      <router-link :to="{ name: 'custom.index' }" class="navbar-brand">
        STAR
      </router-link>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false"
          >
            차트 </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link :to="{ name: 'custom.statistics' }" class="dropdown-item">
              <fa :icon="['fas', 'chart-pie']" />
              종족 빈도수
            </router-link>
            <router-link :to="{ name: 'custom.statistics.ability_kind' }" class="dropdown-item">
              <fa :icon="['fas', 'chart-line']" />
              종족 빈도수
            </router-link>
          </div>
        </li>
        <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        차트 </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="https://star2.cono.kr/statistics"><i class="fas fa-chart-pie"></i> 종족
                            빈도수</a>
                        <a class="dropdown-item" href="https://star2.cono.kr/statistics/ability_kind"><i
                                class="fas fa-chart-line"></i> 종족 빈도수</a>
                    </div>
                </li> -->
        <li class="nav-item">
          <router-link :to="{ name: 'custom.ranking' }" class="nav-link">
            랭킹
          </router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://www.ygosu.com/">커뮤니티</a>
        </li>
      </ul>
    </nav>

    <nuxt />

    <footer class="container footer footer-padding blue">
      <ul class="menu">
        <li>
          <a href="https://www.cono.kr">cono.kr</a>
        </li>
        <li>
          <router-link :to="{ name: 'custom.privacy' }">
            개인정보처리방침
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'custom.feedback' }">
            문의/피드백
          </router-link>
        </li>
      </ul>
      <div class="render">
        Page rendered in 0.0157 seconds [2.24MB] / 스타 전적 검색 ver 2.0 By
        Jun.
      </div>
    </footer>
  </div>
</template>

<style scoped>
/*
@font-face {
    font-family: "SeoulNamsanM";
    src: url("https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_two@1.0/SeoulNamsanM.woff") format("woff");
}
*/

footer {
    flex-shrink: 0;
}

@media (max-width: 992px) {
    .logo {
        font-size: 70px !important;
    }
}

.navbar-dark .navbar-nav .nav-link {
    color: #ddf;
}

.navbar-dark .navbar-nav .nav-link:hover {
    color: #fff;
}

.body.gray {
    background-color: #ebebeb;
}

.body.blue {
    background-color: #5383e9;
}

.body-flex {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
}

footer.gray * {
    color: #333 !important;
}

footer.blue * {
    color: #fff !important;
}

.footer-padding {
    margin-top: 50px;
    padding-bottom: 10px;
}

/* body */
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

/* foorter */
.footer .menu {
    padding: 0;
    margin: 0;
}

.footer .menu li {
    display: inline-block;
}

.footer .render {
    color: #fff;
}

.nav-custom {
    border-bottom: 1px solid #4171d6;
    padding: 8px 16px;
    background-color: #5383e9;
    box-shadow: none;
    font-weight: 400;
}
</style>
