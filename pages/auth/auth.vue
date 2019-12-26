<template>
	<view class="margin-top">
    <form @submit="submit">
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
        	<text>没有账号？</text>
          <navigator url="/pages/auth/register" class="inline-block padding-sm text-blue" open-type="redirect" hover-class="navigator-hover">快速注册</navigator>
        </view>
        <button form-type="submit" class="cu-btn block bg-green shadow lg" :disabled="!formReady" type="">登录</button>
      </view>
    </form>
	</view>
</template>

<script>
  import { mapActions } from 'vuex'
  import { mapGetters } from 'vuex'
	export default {
		data() {
			return {
				username: '',
        password: ''
			}
		},
    computed: {
      ...mapGetters(['isLogged']),
      
      formReady() {
        return (
          this.username.length >= 3 &&
          this.password.length >= 6
        )
      }
    },
		methods: {
      ...mapActions(['login']),
      
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
      
      // 页面打开时使用 code 自动登录一次
      this.attempLogin()
    }
	}
</script>

<style>
  .cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
