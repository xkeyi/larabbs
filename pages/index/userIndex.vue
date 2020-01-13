<template>
	<view>
		<topic-list :syncData="requestData" :syncUrl.sync="requestUrl" ref="topicList"></topic-list>
	</view>
</template>

<script>
  import topicList from '@/components/topic-list.vue'
  
	export default {
    components:{
      topicList
    },
		data() {
			return {
				requestData: {},
        requestUrl: ''
			}
		},
    onLoad(options) {
      this.requestUrl = 'users/' + options.user_id + '/topics'
      this.$nextTick(() => {
        this.$refs.topicList.reload()
      })
    },
    async onPullDownRefresh() {
      await this.$refs.topicList.reload()
      uni.stopPullDownRefresh()
    },
    onReachBottom() {
      this.$refs.topicList.loadMore()
    },
		methods: {
		}
	}
</script>

<style>

</style>
