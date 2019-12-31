<template>
	<view>
    <view class="bg-white">
      <view class="padding">
      	<view class="flex justify-center">
      		<view class="cu-avatar round avatar" @click="updateAvatar" :style="[{ backgroundImage:'url(' + user.avatar + ')' }]"></view>
      	</view>
      </view>
    </view>
    
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
	</view>
</template>

<script>
  import { cloneDeepWith } from 'lodash'
  import { mapGetters, mapActions } from 'vuex'
  import api from '@/utils/api'
  
	export default {
		data() {
			return {
        user: {},
        avatarId: 0
			}
		},
    computed: {
      ...mapGetters(['currentUser'])
    },
		methods: {
      ...mapActions(['setUser']),
      
      async submit() {
        if (this.avatarId !== 0) {
          this.user.avatar_image_id = this.avatarId
        }
        await this.$http.put('user', this.user)
          .then(response => {
            this.setUser(response)
            
            uni.showToast({
              title: '成功修改用户信息！',
              duration: 2000
            })
            
            uni.navigateBack()
          })
      },
      async updateAvatar() {
        // 选择头像
        let file = await uni.chooseImage({
          count: 1,
          sizeType: ['compressed'], //可以指定是原图还是压缩图，默认二者都有
          sourceType: ['album'] //从相册选择
        })
        if (file.length < 2) {
          return
        }
        
        let response = await api.uploadFile({
          'url': 'images',
          filePath: file[1].tempFilePaths[0],
          name: 'image',
          formData: {
            'type': 'avatar'
          },
        })

        // 上传成功成功记录数据
        if (response.statusCode === 201) {
          // 上传结果没有做 JSON.parse，需要手动处理
          let responseData = JSON.parse(response.data)
          this.user.avatar = responseData.path
          this.avatarId = responseData.id
        }
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
  .avatar {
    width: 200rpx;
    height: 200rpx;
  }
</style>
