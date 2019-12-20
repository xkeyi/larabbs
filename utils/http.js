import Fly from 'flyio/dist/npm/wx'

const http = new Fly

//添加请求拦截器
http.interceptors.request.use(
  (request) => {
    // 添加配置
    return request
  }, 
  (error) => {
    return Promise.reject(error)
  }
)
 
//添加响应拦截器，响应拦截器会在then/catch处理之前执行
http.interceptors.response.use(
  (response) => {
    //只将请求结果的data字段返回
    return response.data
  },
  (error) => {
    console.log('response error')
    console.log(error)
    if (!error['response']) {
      return Promise.reject(error)
    }
    
    // 统一错误处理
    let errMsg = ''
    switch (error.response.status) {
      case 422:
        let data = error.response.data.errors

        Object.keys(data).map(function (key) {
          let value = data[key]

          errMsg = value[0]
        })
        break
      case 403:
        errMsg = error.response.data.message || '您没有此操作权限！'
        break
      case 401:
        //
        break
      case 429:
        errMsg = error.response.data.message || '请求太过频繁了,请稍后重试！'
        break
      case 500:
      case 501:
      case 503:
      default:
        errMsg = '服务器出了点小问题，程序员小哥哥要被扣工资了~！'
    }
    
    console.log(errMsg)
    if (errMsg) {
      uni.showToast({
         title: errMsg,
         icon: 'none',
         duration: 2000
      })
    }
    
    return Promise.reject(error.response)
  }
)

//设置请求基地址
http.config.baseURL="http://larabbs6.test/api/v1/"

export function setToken (token) {
  http.config.headers.Authorization = `Bearer ${token}`
}

export default http