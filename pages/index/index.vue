<template>
	<view>
    <view class="cu-list menu-avatar">
    	<view v-for="(topic, index) in topics" :key="item.id" class="cu-item">
    		<view class="cu-avatar radius lg" :style="[{ backgroundImage:'url(' + topic.user.avatar + ')' }]"></view>
        
    		<view class="content">
    			<view>
    				<view class="text-cut">{{ topic.title }}</view>
    			</view>
    			<view class="text-gray text-sm flex">
    				<view class="text-cut">
              {{ topic.category.name }} •  {{ topic.user.name }} • {{ topic.updated_at_diff }}
            </view>
           </view>
    		</view>
    		<view class="action">
    			<view class="cu-tag round bg-grey sm">{{ topic.reply_count }}</view>
    		</view>
    	</view>
      
      <view class="padding text-gray text-sm text-center" v-if="noMoreData">
        <text>没有更多数据了</text>
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
        topics: [],
        // 当前分页
        page: 1,
        noMoreData: false,
        categories: []
			}
		},
		onLoad() {
      this.getTopics()
      this.getCategories()
		},
    async onPullDownRefresh() {
      this.page = 1
      await this.getTopics(1, true)
      uni.stopPullDownRefresh()
    },
    async onReachBottom() {
      // 如果没有更多数据了，直接返回
      if (this.noMoreData) {
        return
      }
      
      this.page = this.page + 1
      await this.getTopics(this.page)
    },
		methods: {
      async getTopics(page = 1, reset = false) {
        uni.showLoading({
          title: '加载中...'
        })
        
        await this.$http.get('topics', {
          page: page,
          include: 'user,category'
        })
        .then(response => {
          let topics = response.data
          topics.forEach(function (topic) {
            topic.updated_at_diff = util.diffForHumans(topic.updated_at)
          })
          // 如果传入参数 reset 为true，则覆盖 topics
          this.topics = reset ? topic : this.topics.concat(topics)
          
          // 根据分页设置是否还有更多数据
          if (response.meta.current_page === response.meta.last_page) {
            this.noMoreData = true
          }
        })
        
        uni.hideLoading()
      },
      async getCategories() {
        // 从缓存中获取分类数据
        let categories = uni.getStorageSync('categories')
        if (categories) {
          this.categories = categories
        }
        await this.$http.get('categories')
          .then(response => {
            this.categories = response.data
            uni.setStorageSync('categories', response.data)
          })
      }
		}
	}
</script>

<style lang="scss">
</style>
