// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuex from 'vuex'
import App from './App'
import VueAxios from 'vue-axios'
import axios from 'axios'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import store from './store'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './global.scss'

const serverURL = 'http://localhost/azercosmos-training-certification/api/public'

Vue.config.productionTip = false
axios.defaults.baseURL = serverURL

Vue.use(BootstrapVue)
Vue.use(Vuex)
Vue.use(VueAxios, axios)

Vue.component('v-icon', Icon)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
