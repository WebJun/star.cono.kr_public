require('dotenv').config()
const { join } = require('path')
const { copySync, removeSync } = require('fs-extra')

module.exports = {
//   ssr: true,
  ssr: false,

  srcDir: __dirname,

  env: {
    apiUrl: process.env.API_URL || process.env.APP_URL + '/api',
    appName: process.env.APP_NAME || 'Star',
    appLocale: process.env.APP_LOCALE || 'ko',
    githubAuth: !!process.env.GITHUB_CLIENT_ID
  },

  head: {
    title: process.env.APP_NAME,
    // titleTemplate: '%s - ' + process.env.APP_NAME,
    titleTemplate: process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '스타크래프트 전적 조회 서비스, 스타 전적 조회 서비스' },
      { hid: 'keywords', name: 'keywords', content: '스타크래프트,전적,조회,와이고수,스타,스타1' },
      { hid: 'author', name: 'author', content: 'Jun' },
      { hid: 'robots', name: 'robots', content: 'index,follow' },
      { property: 'og:locale', content: 'ko_KR' },
      { property: 'og:type', content: 'website' },
      { property: 'og:title', content: '스타 전적 조회 서비스' },
      { property: 'og:description', content: '스타크래프트 전적 조회 서비스, 스타 전적 조회 서비스' },
      { property: 'og:image', content: '' },
      { property: 'og:site_name', content: 'star-record' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ],
    htmlAttrs: {
      lang: 'ko'
    }
  },

  loading: { color: '#007bff' },

  router: {
    middleware: ['locale', 'check-auth']
  },

  css: [
    { src: '~assets/sass/app.scss', lang: 'scss' }
  ],

  plugins: [
    '~components/global',
    // '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    '~plugins/axios2',
    '~plugins/fontawesome',
    '~plugins/nuxt-client-init',
    '~plugins/vue-google-adsense.client',
    '~plugins/toast.js',
    { src: '~plugins/bootstrap', mode: 'client' }
  ],

  modules: [
    '@nuxtjs/router',
    'bootstrap-vue/nuxt'
  ],

  build: {
    extractCSS: true
  },

  hooks: {
    generate: {
      done (generator) {
        // Copy dist files to public/_nuxt
        if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
          const publicDir = join(generator.nuxt.options.rootDir, 'public', '_nuxt')
          removeSync(publicDir)
          copySync(join(generator.nuxt.options.generate.dir, '_nuxt'), publicDir)
          copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDir, 'index.html'))
          removeSync(generator.nuxt.options.generate.dir)
        }
      }
    }
  }
}
