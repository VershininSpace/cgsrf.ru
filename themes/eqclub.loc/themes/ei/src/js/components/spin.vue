<template lang="pug">
	div.loading
		img(src="assets/img/preload.svg")

</template>

<script>
  export default {

  }
</script>

<style lang="scss">
	.loading{
		position: fixed;
		display: block;
		left: 0;
		right: 0;
		top: 0;
		width: 100%;
		height: 100vh;
		overflow: hidden;
		background-color: rgba(0, 0, 0, 0.9);
		z-index: 99999999;
		text-align: center;
		img{
			position: absolute;
			width: 150px;
			height: 150px;
			display: block;
			margin: auto;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
		}
	}
</style>