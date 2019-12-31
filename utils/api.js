const uploadFile = async (options = {}) => {
  // 显示loading
  uni.showLoading({title: '上传中...'})

  // 获取 token (这里可以单独抽出一个方法来获取token,如果token过期,刷新之后再返回新的token)
  let accessToken = uni.getStorageSync('access_token')

  // 拼接url
  options.url = 'http://larabbs6.test/api/v1/' + options.url
  let header = options.header || {}
  // 将 token 设置在 header 中
  header.Authorization = 'Bearer ' + accessToken
  options.header = header

  // 上传文件
  let response =  await uni.uploadFile(options)

  // 隐藏 loading
  uni.hideLoading()
  
  // 可以在这里做统一的错误处理
  
  return response[1]
}

export default {
  uploadFile
}