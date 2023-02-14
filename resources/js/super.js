/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap'

import Vue from 'vue'

import Users from './Root/views/users/Users.vue'
import Admins from './Root/views/admins/Admins.vue'
import Porterias from './Root/views/porterias/Porterias.vue'
import Upload from './Root/views/invoices/Upload.vue'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  components: {
    Users,
    Admins,
    Porterias,
    Upload
  }
})
