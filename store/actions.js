import http from '@/utils/http'
import { isEmpty } from 'lodash'

export const login = ({ dispatch }, payload) => {
  return http.post('weapp/authorizations', payload)
          .then(response => {
            console.log(response)
            dispatch('setToken', response.access_token)
             
            return Promise.resolve()
          })
          .then(() => {
            // dispatch('loadUser')
          })
}

export const setToken = ({ commit }, token) => {
  // Commit the mutations
  commit('SET_TOKEN', token)
  
  return Promise.resolve(token) //keep promise chain
}