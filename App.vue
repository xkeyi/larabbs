<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
      uni.login({
        provider: 'weixin',
        success: function (loginRes) {
          console.log(loginRes)
        }
      });
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
  @import "common/colorui/main.css";
  @import "common/colorui/icon.css";
  .page {
  	height: 100Vh;
  	width: 100vw;
  }
</style>
