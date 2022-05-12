<template>
	<div class="wait" v-if="wait">
		<div class="spinner">
			<svg viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle class="length" fill="none" stroke-width="8" stroke-linecap="round" cx="33" cy="33" r="28"></circle>
			</svg>
			<!--<svg viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle fill="none" stroke-width="8" stroke-linecap="round" cx="33" cy="33" r="28"></circle>
			</svg>
			<svg viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle fill="none" stroke-width="8" stroke-linecap="round" cx="33" cy="33" r="28"></circle>
			</svg>
			<svg viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle fill="none" stroke-width="8" stroke-linecap="round" cx="33" cy="33" r="28"></circle>
			</svg>-->
		</div>
		<div class="buymodal__warning" v-show="$root.warn">ОЖИДАЙТЕ ОТВЕТА СЕРВИСА. <small>При большом количестве обращений ответ сервиса может достигать 3-5 минут. Поэтому не паникуйте, а возьмите чашечку кофе, отвлекитесь от всего и чуть-чуть подождите вместе с нами. :)</small></div>
	</div>
</template>

<script>
  //import { bus } from './bus.js'
  export default {
    name: "wait",
    data() {
      return {

      }
    },
    props: {
      wait: '',
      items: ''
    },
    methods: {
      changeState(data){
        this.wait = data;
      }
    },
	  mounted(){
      //bus.$on('wait', this.changeState)
	  }
  }
</script>

<style lang="scss" scoped>
	.wait{
		position: fixed;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: rgba(#57B846, 0.6);
		z-index: 9999;
	}
	.spinner {
		width: 66px;
		height: 66px;

		animation: contanim 2s linear infinite;
	}

	$colors: #fff, #ff5757, #F6BB67, #fff;
	$d: 175.6449737548828;

	svg {
		width: 100%;
		height: 100%;

		left: 0;
		top: 0;
		position: absolute;

		transform: rotate(-90deg);

		@for $i from 1 through 4 {
			&:nth-child(#{$i}) circle {
				stroke: nth($colors, $i);
				stroke-dasharray: 1, 300;
				stroke-dashoffset: 0;

				animation: strokeanim 3s calc(.2s * (#{$i})) ease infinite;
				transform-origin: center center;
			}
		}
	}

	@keyframes strokeanim {
		0% {
			stroke-dasharray: 1, 300;
			stroke-dashoffset: 0;
		}
		50% {
			stroke-dasharray: 120, 300;
			stroke-dashoffset: -$d / 3;
		}
		100% {
			stroke-dasharray: 120, 300;
			stroke-dashoffset: -$d;
		}
	}

	@keyframes contanim {
		100% {
			transform: rotate(360deg)
		}
	}
</style>