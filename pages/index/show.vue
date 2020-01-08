<template>
	<view>
		<view class="cu-card no-card">
			<view class="cu-item shadow">
				<view class="cu-list menu-avatar">
					<view class="cu-item">
						<view class="cu-avatar round lg" :style="[{ backgroundImage:'url(' + topic.user.avatar + ')' }]"></view>
						<view class="content flex-sub">
							<view>{{ topic.user.name }}</view>
							<view class="text-gray text-sm flex justify-between">
								{{ topic.user.introduction || '' }}
							</view>
						</view>
					</view>
				</view>
        
        <view class="padding">
          <view class="text-black text-bold">
            {{ topic.title }}
          </view>
          
          <view class="margin-top margin-bottom text-gray text-sm flex">
            <view class="text-cut">
              {{ topic.category.name }} •  {{ topic.updated_at_diff }} • {{ topic.updated_at_diff }} 次回复
            </view>
          </view>
          
          <view class="text-content">
            <rich-text :nodes="topic.body"></rich-text>
          </view>	
        </view>
			</view>
		</view>
	</view>
</template>

<script>
  import util from '@/utils/util'
  
	export default {
		data() {
			return {
				// 话题数据
        topic: null
			}
		},
		methods: {
			async getTopic(id) {
        uni.showLoading({
          title: '加载中...'
        })
        
        await this.$http.get('topics/'+id, {
          include: 'user,category'
        })
        .then(response => {
          let topic = response
          
          // 格式化 updated_at
          topic.updated_at_diff = util.diffForHumans(topic.updated_at)
          
          this.topic = topic
          console.log(this.topic)
        })
        uni.hideLoading()
      }
		},
    onLoad(options) {
      this.getTopic(options.id)
    }
	}
</script>

<style>

</style>
