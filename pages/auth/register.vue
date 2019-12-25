<template>
	<view>
    <view class="margin-top margin-left text-gray">Larabbs 注册</view>
    <form @submit="submit">
      <view class="cu-form-group">
        <view class="title">手机号</view>
          <input placeholder="输入手机号" v-model="phone"></input>
          <button class='cu-btn bg-green shadow' type="button" @click="getCaptchaCode">获取验证码</button>
      </view>
      
      <view class="cu-form-group">
        <view class="title">用户名</view>
        <input placeholder="手机号或邮箱" v-model="username"></input>
      </view>
      <view class="cu-form-group">
        <view class="title">密码</view>
        <input placeholder="输入密码" v-model="password" type="password"></input>
      </view>
      
      <view class="padding flex flex-direction">
        <view class="solid-bottom text-gray margin-bottom-xs">
        	<text>已有账号？</text>
          <navigator url="/pages/auth/auth" class="inline-block padding-sm text-blue" open-type="redirect" hover-class="navigator-hover">快速登录</navigator>
        </view>
        <button form-type="submit" class="cu-btn block bg-green shadow lg" :disabled="!formReady" type="">注册</button>
      </view>
    </form>
    
    <!-- 图片验证码模态窗 -->
    <view class="cu-modal" :class="captchaModalShow ? 'show' : ''">
    	<view class="cu-dialog">
    		<view class="padding-xl">
    			<view class="cu-form-group padding-0">
    			  <input placeholder="图片验证码" v-model="captchaValue"></input>
    			  <image style="width: 120px;height: 55px;;" :src="captcha.imageContent" @click="getCaptchaCode"></image>
    			</view>
    		</view>
    		<view class="cu-bar bg-white">
          <view class="action margin-0 flex-sub  solid-left text-green">确定</view>
    		</view>
    	</view>
    </view>
    
	</view>
</template>

<script>
  import { mapActions } from 'vuex'
  import { mapGetters } from 'vuex'
  
	export default {
		data() {
			return {
        phone: '',
        // 图片验证码 modal 是否显示
        captchaModalShow: false,
        // 用户输入的验证码
        captchaValue: null,
        // 图片验证码 key 及过期时间
        captcha: {},
				username: '',
        password: ''
			}
		},
    computed: {
      ...mapGetters(['isLogged']),
      
      formReady() {
        return true
        return (
          this.username.length >= 3 &&
          this.password.length >= 6
        )
      }
    },
		methods: {
      ...mapActions(['login']),
      
      async getCaptchaCode() {
        if (!(/^1[3456789]\d{9}$/.test(this.phone))) {
          uni.showToast({
             title: '请输入正确的手机号',
             icon: 'none',
             duration: 2000
          })
          return false
        }
        
        await this.$http.post('captchas', {phone: this.phone})
          .then(response => {
            console.log(response)
            this.captcha = {
              key: response.captcha_key,
              imageContent: response.captcha_image_content,
              expiredAt: Date.parse(response.expired_at)
            }
            
            // 打开 model
            this.captchaModalShow = true
          })
      },
      submit() {
        let params = {
          username: this.username,
          password: this.password
        }
        
        this.attempLogin(params)
      },
      async attempLogin(params = {}) {
        // code 只能使用一次，所以每次单独调用
        let loginCode = await uni.login({provider: 'weixin'})
        params.code = loginCode[1].code
        
        uni.showLoading({
            title: '正在登录...'
        })
        
        try {
          await this.login(params)
          
          uni.showToast({
            title: '欢迎回来！',
            duration: 2000
          })
          
          uni.navigateBack()
        } catch (e) {
          if (e.status === 401 && params.username) {
            uni.showToast({
              title: '用户名或密码错误！',
              icon: 'none',
              duration: 2000
            })
          }
        }
        
        uni.hideLoading()
      }
		},
    onShow() {
      if (this.isLogged) {
        uni.switchTab({
          url: '/pages/index/index'
        });
      }
      
      // 页面打开时判断是否登录，登录直接跳转到首页
      // this.attempLogin()
    }
	}
</script>

<style>
  .cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
