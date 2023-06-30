<template></template>

<script>
export default {
	data() { 
		return { 
			windowWidth: window.innerWidth 
		} 
	},
  	mounted() {
    	window.addEventListener('resize', () => {
      		this.windowWidth = window.innerWidth
      		this.$emit('detectMobile', this.windowWidth <= 768)
    	})

    	if(this.windowWidth <= 768){
    		this.$emit('detectMobile', this.windowWidth <= 768)
    	}

	 },
  	computed: {
    	isMobile() {
    		this.$emit('detectMobile', this.windowWidth <= 768)
      		return this.windowWidth <= 768
    	}
  	}
}
</script>