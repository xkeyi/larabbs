// plugins and src are alias. see client/build/webpack.base.conf.js
import { setToken as httpSetToken } from '@/utils/http'
// import localforage from 'localforage'

export const tokenPlugin = store => {
  store.subscribe((mutation, state) => {

    if (mutation.type === 'SET_TOKEN') {
      httpSetToken(state.token)

      // localforage.setItem('cha-vue-store-token', state.token)
    }
  })
}
