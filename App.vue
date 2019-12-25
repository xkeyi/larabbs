<script>
  import { mapActions } from 'vuex'
  
	export default {
		onLaunch() {
			console.log('App Launch')
      
      this.syncToken()
		},
		onShow() {
			console.log('App Show')
		},
		onHide() {
			console.log('App Hide')
		},
    methods: {
      ...mapActions(['checkUserToken']),
      
      async syncToken() {
        await this.checkUserToken()
      }
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
  .inline-block {
    display: inline-block;
  }
</style>
