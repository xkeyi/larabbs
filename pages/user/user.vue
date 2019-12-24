<template>
	<view class="page">
    <view v-if="isLogged">
      <view class="bg-white">
        <view class="padding">
        	<view class="flex justify-center">
        		<view class="cu-avatar round avatar" :style="[{ backgroundImage:'url(' + currentUser.avatar + ')' }]"></view>
        	</view>
        </view>
        
        <view class="padding-bottom">
          <view class="flex">
            <view class="flex-sub text-center">
            	<view class="solid-bottom text-lg">
            		<text class="text-black">{{ currentUser.name }}</text>
            	</view>
            </view>
          </view>
        </view>
      </view>
      
      <view class="bg-white margin-top">
        <view class="cu-list menu sm-border">
        	<view class="cu-item arrow">
        		<view class="content">
        			<text class="cuIcon-tagfill text-red  margin-right-xs"></text>
        			<text class="text-grey">标签</text>
        		</view>
        	</view>
          
          <view class="cu-item arrow">
          	<view class="content">
          		<text class="cuIcon-tagfill text-red  margin-right-xs"></text>
          		<text class="text-grey">标签</text>
          	</view>
          </view>
         
          <view class="cu-item arrow">
        		<view class="content">
        			<text class="cuIcon-tagfill text-red  margin-right-xs"></text>
        			<text class="text-grey">标签</text>
        		</view>
        	</view>
        </view>
      </view>

      <view class="bg-white margin">
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
  .avatar {
    width: 200rpx;
    height: 200rpx;
  }
</style>
