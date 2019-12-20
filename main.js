import Vue from 'vue'
import App from './App'
import http from '@/utils/http'
import store from './store'

Vue.config.productionTip = false

Vue.prototype.$store = store;
Vue.prototype.$http = http

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
