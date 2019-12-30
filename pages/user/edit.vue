<template>
	<view class="margin-top">
    <form @submit="submit">
      <view class="cu-form-group">
        <view class="title">姓名</view>
          <input placeholder="请输入姓名" v-model="user.name"></input>
      </view>
      <view class="cu-form-group">
        <view class="title">邮箱</view>
        <input placeholder="请输入邮箱" v-model="user.email"></input>
      </view>
      
      <view class="cu-form-group align-start">
      	<view class="title">简介</view>
      	<textarea maxlength="80" v-model="user.introduction" placeholder="请输入个人简介"></textarea>
      </view>
      
      <view class="padding flex flex-direction">
        <button form-type="submit" class="cu-btn block bg-green shadow lg" type="">修改</button>
      </view>
    </form>
	</view>
</template>

<script>
  import { cloneDeepWith } from 'lodash'
  import { mapGetters, mapActions } from 'vuex'
  
	export default {
		data() {
			return {
        user: {}
			}
		},
    computed: {
      ...mapGetters(['currentUser'])
    },
		methods: {
      ...mapActions(['setUser']),
      
      async submit() {
        await this.$http.put('user', this.user)
          .then(response => {
            this.setUser(response)
            
            uni.showToast({
              title: '成功修改用户信息！',
              duration: 2000
            })
          })
      }
		},
    onShow() {
      this.user = cloneDeepWith(this.currentUser)
    }
	}
</script>

<style>
  .cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
