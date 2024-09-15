import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

// const routes = [
//   { path: '/', name: 'welcome', component: page('welcome.vue') },

//   { path: '/login', name: 'login', component: page('auth/login.vue') },
//   { path: '/register', name: 'register', component: page('auth/register.vue') },
//   { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
//   { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
//   { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
//   { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

//   { path: '/home', name: 'home', component: page('home.vue') },
//   {
//     path: '/settings',
//     component: page('settings/index.vue'),
//     children: [
//       { path: '', redirect: { name: 'settings.profile' } },
//       { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
//       { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
//     ]
//   }
// ]
const routes = [
  // 인증
  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  // 관리자
  { path: '/admin/Main', name: 'custom.admin.main', component: page('custom/admin_main.vue') },

  // 프론트
  { path: '/', name: 'custom.index', component: page('custom/index.vue') },
  { path: '/ranking/lists', name: 'custom.ranking', component: page('custom/ranking.vue') },
  { path: '/statistics', name: 'custom.statistics', component: page('custom/statistics.vue') },
  { path: '/statistics/ability_kind', name: 'custom.statistics.ability_kind', component: page('custom/statistics_ability_kind.vue') },
  { path: '/main/privacy', name: 'custom.privacy', component: page('custom/main_privacy.vue') },
  { path: '/main/feedback', name: 'custom.feedback', component: page('custom/main_feedback.vue') },
  { path: '/:area/:toon', name: 'custom.search', component: page('custom/search.vue') } // 순서 중요. 맨 마지막
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
