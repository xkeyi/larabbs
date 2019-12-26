<template>
	<view class="margin-top">
    <form @submit="submit">
      <view class="cu-form-group">
        <view class="title">手机号</view>
          <input placeholder="输入手机号" v-model="phone" :disabled="phoneDisabled"></input>
          <button class='cu-btn bg-green shadow' type="button" @click="getCaptchaCode">获取验证码</button>
      </view>
      <view class="cu-form-group">
        <view class="title">验证码</view>
        <input placeholder="短信验证码" v-model="verification_code"></input>
      </view>
      
      <view class="cu-form-group">
        <view class="title">姓名</view>
        <input placeholder="请输入姓名" v-model="name"></input>
      </view>
      <view class="cu-form-group">
        <view class="title">密码</view>
        <input placeholder="请输入密码" v-model="password" type="password"></input>
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
          <view class="action margin-0 flex-sub  solid-left text-green" @click="sendVerificationCode">确定</view>
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
        // 手机输入框 disabled
        phoneDisabled: false,
        // 图片验证码 modal 是否显示
        captchaModalShow: false,
        // 用户输入的验证码
        captchaValue: null,
        // 图片验证码 key 及过期时间
        captcha: {},
        // 短信验证码 key 及过期时间
        verificationCode: {},
        verification_code: '',
				name: '',
        password: ''
			}
		},
    computed: {
      ...mapGetters(['isLogged']),
      
      formReady() {
        return (
          this.name.length >= 3 &&
          this.password.length >= 6 &&
          this.verificationCode.key
        )
      }
    },
		methods: {
      ...mapActions(['register']),
      
      resetRegister() {
        this.phone = ''
        this.phoneDisabled = false
        this.captchaModalShow = false
        this.captchaValue = null
        this.captcha = {}
        this.verificationCode = {}
      },
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
            this.captcha = {
              key: response.captcha_key,
              imageContent: response.captcha_image_content,
              expiredAt: Date.parse(response.expired_at)
            }
            
            // 打开 model
            this.captchaModalShow = true
          })
      },
      async sendVerificationCode() {
        if (!this.captchaValue) {
          uni.showToast({
             title: '请输入图片验证码',
             icon: 'none',
             duration: 2000
          })
          return false
        }
        
        // 检查验证码是否过期，重置流程
        if (new Date().getTime() > new Date(this.captcha.expiredAt).getTime()) {
          uni.showToast({
             title: '请输入图片验证码',
             icon: 'none',
             duration: 2000
          })
          
          this.resetRegister()
          return false
        }
        
        try {
          await this.$http.post('verificationCodes', {
            captcha_key: this.captcha.key,
            captcha_code: this.captchaValue
          })
          .then(response => {
            // 记录 key 和过期时间
            this.verificationCode = {
              key: response.key,
              expiredAt: response.expired_at
            }
            
            // 关闭 model
            this.captchaModalShow = false
            // 手机输入框 disabled
            this.phoneDisabled = true
          })
        } catch(e) {
          // 重新获取图片验证码
          await this.getCaptchaCode()
        }
      },
      submit() {
        // 检查验证码是否已发送
        if (!this.verificationCode.key) {
          uni.showToast({
             title: '请先发送验证码',
             icon: 'none',
             duration: 2000
          })
          return false
        }
        // 检查验证码是否已经过期
        if (new Date().getTime() > this.verificationCode.expiredAt) {
          uni.showToast({
             title: '验证码已过期',
             icon: 'none',
             duration: 2000
          })
          
          this.resetRegister()
          return false
        }
        
        let params = {
          verification_code: this.verification_code,
          verification_key: this.verificationCode.key,
          name: this.name,
          password: this.password
        }
        
        this.attempRegister(params)
      },
      async attempRegister(params) {
        // 参数中增加 code，用户绑定当前用户
        let loginCode = await uni.login({provider: 'weixin'})
        params.code = loginCode[1].code
        
        uni.showLoading({
          title: '正在保存...'
        })
        
        try {
          await this.register(params)
          
          uni.hideLoading()
          
          uni.showToast({
            title: '欢迎加入！',
            duration: 2000
          })
        } catch (e) {
          //
        }
        
        // uni.switchTab({
        //   url: '/pages/index/index'
        // });
      }
		},
    onShow() {
      if (this.isLogged) {
        uni.switchTab({
          url: '/pages/index/index'
        });
      }
    }
	}
</script>

<style>
  .cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
