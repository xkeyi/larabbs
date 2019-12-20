import { isEmpty } from 'lodash'

export const isLogged = (state) => {
  let token = state.token
  
  return !isEmpty(token)
}

export const currentUser = (state) => {
  return state.user
}