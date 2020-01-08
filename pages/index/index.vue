<template>
	<view>
    <view class="bg-white flex solid-bottom padding justify-between" style="border-bottom: 1px solid #CCCCCC;">
    	<view class="text-lg">{{ currentCategory.name || '话题' }}</view>
    	<view style="font-size: 26px;">
        <text class="lg text-gray cuIcon-cascades" @click="categoryTag"></text>
      </view>
    </view>
    
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
    
    <uni-drawer :visible="isShowCategories" mode="left" mask="true">
      <view class="cu-list menu sm-border">
      	<view v-for="category in categories" :key="category.id" class="cu-item" @click="changeCategory(category.id)">
      		<view class="content">
      			<text class="text-grey">{{ category.name }}</text>
      		</view>
      	</view>
      </view>
    </uni-drawer>
  </view>
</template>

<script>
  import uniDrawer from '@/components/uni-drawer/uni-drawer.vue'
  import util from '@/utils/util'
  
	export default {
    components: {
      uniDrawer
    },
		data() {
			return {
				// 话题数据
        topics: [],
        // 当前分页
        page: 1,
        noMoreData: false,
        categories: [],
        currentCategory: {},
        isShowCategories: false
			}
		},
    computed: {
      currentCategoryId() {
        return this.currentCategory.id || 0
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
          category_id: this.currentCategoryId,
          include: 'user,category'
        })
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
      },
      categoryTag() {
        this.isShowCategories = !this.isShowCategories
      },
      async changeCategory(id = 0) {
        // 关闭分类列表
        this.isShowCategories = false
        // 点击当前分类直接返回
        if (Number(id) === this.currentCategoryId) {
          return
        }
        
        // 重置分页和是否有更多数据
        this.noMoreData = false
        this.page = 1
        
        // 找到选中的分类
        this.currentCategory = id ? this.categories.find(category => category.id === id) : {}
        
        await this.getTopics(1, true)
      }
		}
	}
</script>

<style lang="scss">
</style>
