<template>
	<view class="page">
    <view v-if="user.id">
      已经登录
    </view>
    <view v-else>
      <navigator url="/pages/auth/auth" hover-class="navigator-hover">
        <button type="default">去登录</button>
      </navigator>
      
      <view class="padding">
      	<button class="cu-btn block bg-blue margin-tb-sm lg" disabled type="">无效状态</button>
      	<button class="cu-btn block line-blue margin-tb-sm lg" disabled>无效状态</button>
      </view>
    </view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
        user: {}
			}
		},
		methods: {
		}
	}
</script>

<style>
	
</style>
