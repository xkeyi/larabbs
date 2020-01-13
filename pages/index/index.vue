<template>
	<view>
    <view class="bg-white flex solid-bottom padding justify-between" style="border-bottom: 1px solid #CCCCCC;">
    	<view class="text-lg">{{ currentCategory.name || '话题' }}</view>
    	<view style="font-size: 26px;">
        <text class="lg text-gray cuIcon-cascades" @click="categoryTag"></text>
      </view>
    </view>
    
    <topic-list :syncData="requestData" ref="topicList"></topic-list>
    
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
  import topicList from '@/components/topic-list.vue'
  import util from '@/utils/util'
  
	export default {
    components: {
      uniDrawer,
      topicList
    },
		data() {
			return {
        categories: [],
        currentCategory: {},
        isShowCategories: false,
        requestData: {}
			}
		},
    computed: {
      currentCategoryId() {
        return this.currentCategory.id || 0
      }
    },
		onLoad() {
      this.getCategories()
      this.$refs.topicList.reload()
		},
    async onPullDownRefresh() {
      await this.$refs.topicList.getTopics()
      
      uni.stopPullDownRefresh()
    },
    onReachBottom() {
      this.$refs.topicList.loadMore()
    },
		methods: {
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
        
        // 找到选中的分类
        this.currentCategory = id ? this.categories.find(category => category.id === id) : {},
        
        this.requestData.category_id = this.currentCategoryId
        
        this.$nextTick(() => {
          this.$refs.topicList.reload()
        })
      }
		}
	}
</script>

<style lang="scss">
</style>
