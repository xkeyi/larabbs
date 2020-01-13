<template>
  <view class="cu-list menu-avatar">
    <navigator v-for="(topic, index) in topics" :key="topic.id" class="cu-item" :url="'/pages/index/show?id='+topic.id"  hover-class="navigator-hover">
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
    </navigator>
    
    <view class="padding text-gray text-sm text-center" v-if="noMoreData">
      <text>没有更多数据了</text>
    </view>
  </view>
</template>

<script>
  import util from '@/utils/util'
  
	export default {
    name: 'topicList',
    props: {
      // 父页面传入，请求参数
      syncData: {
        type: Object,
        default: ()=> {
          return {}
        }
      },
      // 父页面传入，请求 url
      syncUrl: {
        type: String,
        default: 'topics'
      }
    },
		data() {
			return {
				// 话题数据
        topics: [],
        // 有没有更多数据
        noMoreData: false,
        // 是否在加载中
        isLoading: false
			}
		},
		methods: {
      // 获取话题数据
      async getTopics(reset = false) {
        if (!this.syncData.page) {
          this.syncData.page = 1
        }
        this.syncData.include = 'user,category'
        
        uni.showLoading({
          title: '加载中...'
        })
        
        await this.$http.get(this.syncUrl, this.syncData)
        .then(response => {
          let topics = response.data
          topics.forEach(function (topic) {
            topic.updated_at_diff = util.diffForHumans(topic.updated_at)
          })
          // 如果传入参数 reset 为true，则覆盖 topics
          this.topics = reset ? topics : this.topics.concat(topics)
          
          // 根据分页设置是否还有更多数据
          if (response.meta.current_page === response.meta.last_page) {
            this.noMoreData = true
          }
        })
        uni.hideLoading()
      },
      // 加载更多
      async loadMore() {
        // 如果没有更多数据，或者正在加载，直接返回
        if (this.noMoreData || this.isLoading) {
          return
        }
        
        // 开始请求之前设置 isLoading 为true
        this.isLoading = true
        this.syncData.page = this.syncData.page + 1
        await this.getTopics()
        
        // 开始结束后设置 isLoading 为 false
        this.isLoading = false
      },
      // 重新加载
      async reload() {
        this.noMoreData = false
        this.syncData.page = 1
        
        await this.getTopics(true)
      }
		}
	}
</script>

<style lang="scss">
</style>
