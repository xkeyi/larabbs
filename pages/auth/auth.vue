<template>
	<view>
    <view class="margin-top margin-left text-df text-gray">Larabbs 登录</view>
    <form @submit="login">
      <view class="cu-form-group">
        <view class="title">用户名</view>
        <input placeholder="手机号或邮箱" v-model="username"></input>
      </view>
      <view class="cu-form-group">
        <view class="title">密码</view>
        <input placeholder="输入密码" v-model="password" type="password"></input>
      </view>
      
      <view class="padding flex flex-direction">
        <button form-type="submit" class="cu-btn block bg-green shadow lg" :disabled="!formReady" type="">登录</button>
      </view>
    </form>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				username: '',
        password: ''
			}
		},
    computed: {
      formReady() {
        return (
          this.username.length >= 3 &&
          this.password.length >= 6
        )
      }
    },
		methods: {
      async login() {
        console.log('login')
        await this.$http.post('authorizations', {
          username: this.username,
          password: this.password
        })
        .then(response => {
          console.log(response)
        })
        .then((error) => {
          console.log(error)
        })
      }
		}
	}
</script>

<style>
  .cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
