import http from '@/utils/http'
import { isEmpty } from 'lodash'

export const login = ({ dispatch }, payload) => {
  return http.post('weapp/authorizations', payload)
          .then(response => {
            uni.setStorageSync('access_token', response.access_token)
            uni.setStorageSync('access_token_expired_at', new Date().getTime() + response.expires_in * 1000)
            
            dispatch('setToken', response.access_token)
             
            return Promise.resolve()
          })
          .then(() => {
            dispatch('loadUser')
          })
}

export const setToken = ({ commit }, token) => {
  // Commit the mutations
  commit('SET_TOKEN', token)
  
  return Promise.resolve(token) //keep promise chain
}

export const loadUser = ({ dispatch }) => {
  http.get('user')
      .then(user => {
        dispatch('setUser', user)
      })
      .catch(() => {
        // 退出登录
        console.log('获取当前用户失败了')
        dispatch('logout')
      })
}

export const setUser = ({ commit }, user) => {
  // Commit the mutations
  commit('SET_USER', user)
  
  Promise.resolve(user) // keep promise chain
}

export const logout = ( {dispatch }) => {
  http.delete('authorizations/current')
      .then(() => {
        uni.clearStorage()
        
        dispatch('setToken', null)
        
        return Promise.resolve()
      })
      .then(() => {
        dispatch('setUser', {})
      })
}

export const checkUserToken = ({ dispatch }) => {
  // 从缓存中取出 Token
  uni.getStorage({key: 'access_token'})
    .then(res => {
      let token = res[1].data
      if (isEmpty(token)) {
        return Promise.reject('NO_TOKEN')
      }
      
      return dispatch('setToken', token) // keep promise chain
    })
    .then(() => {
      dispatch('loadUser')
    })
}

export const register = ({ dispatch }, payload) => {
  return http.post('weapp/users', payload)
    .then(response => {
      uni.setStorageSync('access_token', response.access_token)
      uni.setStorageSync('access_token_expired_at', new Date().getTime() + response.expires_in * 1000)
      
      dispatch('setToken', response.access_token)
       
      return Promise.resolve()
    })
    .then(() => {
      dispatch('loadUser')
    })
}
