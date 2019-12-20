
// 核对登录
function checklogin() {
	// access_token
	let access_token = uni.getStorageSync('access_token')
	let access_token_expired_at = uni.getStorageSync('access_token_expired_at')
	
	// 用户信息
	let userinfo = uni.getStorageSync('userinfo')
  
	return {
		access_token,
		access_token_expired_at,
		userinfo
	}
}

export default {
  checklogin
}