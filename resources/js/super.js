/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')
import 'vue-select/dist/vue-select.css'
import vSelect from 'vue-select' 
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import Toasted from 'vue-toasted'

import Vuetify from 'vuetify'
//import "vuetify/dist/vuetify.min.css"

Vue.use(Toasted)
Vue.use(Vuetify)
Vue.use(Loading)

Vue.component('v-select', vSelect)
Vue.component('pagination', require('laravel-vue-pagination'))

const files = require.context('./components', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const files2 = require.context('./views/Super', true, /\.vue$/i);
files2.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files2(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  vuetify: new Vuetify({
    icons:{
      iconfont:'md'
    },
    theme:{
      light:true
    }
  }),
  el: '#app'
});