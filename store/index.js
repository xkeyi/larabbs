import Vue from 'vue'
import Vuex from 'vuex'
import state from './state'
import mutations from './mutations'
import { tokenPlugin } from './plugin'
import * as getters from './getters'
import * as actions from './actions'

Vue.use(Vuex)

const store = new Vuex.Store({
  state,
  getters,
  mutations,
  actions,
  plugins: [tokenPlugin]
})

export default store