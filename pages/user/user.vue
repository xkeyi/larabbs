<template>
	<view class="page">
    <view v-if="isLogged">
      <view>已经登录</view>
      <view>姓名：{{ currentUser.name }}</view>
      <view class="padding">
      	<button type="button" @click="logout" class="cu-btn block bg-red margin-tb-sm lg">退出登录</button>
      </view>
    </view>
    <view v-else>
      <navigator url="/pages/auth/auth" hover-class="navigator-hover">
        <button type="default">去登录</button>
      </navigator>
    </view>
	</view>
</template>

<script>
  import { mapGetters } from 'vuex'
  import { mapActions } from 'vuex'
  
	export default {
		data() {
			return {
			}
		},
    computed: {
      ...mapGetters(['isLogged', 'currentUser'])
    },
		methods: {
      ...mapActions(['logout'])
		},
    onShow() {
    }
	}
</script>

<style>
</style>
