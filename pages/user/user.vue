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
              <view class="text-cut" style="width:600rpx;display: inline-block; padding-top: 10px;">{{ currentUser.introduction || '' }}</view>
            </view>
          </view>
        </view>
      </view>
      
      <view class="bg-white margin-top">
        <view class="cu-list menu sm-border">
        	<view class="cu-item arrow">
        		<view class="content">
        			<text class="cuIcon-message  margin-right-xs"></text>
        			<text class="text-grey">我的消息</text>
        		</view>
        	</view>
          
          <view class="cu-item arrow">
          	<view class="content">
          		<text class="cuIcon-list margin-right-xs"></text>
          		<text class="text-grey">我的话题</text>
          	</view>
          </view>
         
          <view class="cu-item arrow">
        		<view class="content">
        			<text class="cuIcon-edit margin-right-xs"></text>
        			<text class="text-grey">我的回复</text>
        		</view>
        	</view>
          
          <navigator  url="/pages/user/edit" class="cu-item arrow">
            <view class="content">
              <text class="cuIcon-settings margin-right-xs"></text>
              <text class="text-grey">编辑资料</text>
            </view>
          </navigator>
        </view>
      </view>

      <view class="bg-white margin">
        <button type="button" @click="logout" class="cu-btn block bg-red margin-tb-sm lg">退出登录</button>
      </view>
    </view>
    
    <view v-else>
      <view class="bg-white">
        <view class="padding">
        	<view class="flex justify-center">
            <view class="cu-avatar round avatar text-xsl">
            	<text class="cuIcon-people"></text>
            </view>
        	</view>
        </view>
        
        <view class="padding-bottom">
          <view class="flex">
            <view class="flex-sub text-center">
            	<view class="solid-bottom text-lg">
            		<text>您还未登录，</text>
                <navigator url="/pages/auth/auth" class="inline-block padding-sm text-blue" hover-class="navigator-hover">立即登录</navigator>
            	</view>
            </view>
          </view>
        </view>
        
      </view>
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
