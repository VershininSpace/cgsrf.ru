<template lang="pug">
	div.youtube(uk-lightbox)
		.youtube__item
			//a(:href="'#' + link", uk-toggle='')
			a(:href="'https://www.youtube.com/watch?v=' + link")
				img(:src="'//img.youtube.com/vi/' + link + '/mqdefault.jpg'")
				.youtube__button
		//.uk-flex-top(uk-modal='' :id="link")
			.uk-modal-dialog.uk-width-auto.uk-margin-auto-vertical
				button.uk-modal-close-outside(type='button', uk-close='')
				iframe(:src="'https://www.youtube-nocookie.com/embed/' + link", width='560', height='315', frameborder='0', uk-video='' allow="autoplay; encrypted-media" allowfullscreen)
				//iframe(:src="'https://www.youtube.com/embed/' + link + '?rel=0&amp;showinfo=0'", width='560', height='315', frameborder='0', uk-video='' allow="autoplay; encrypted-media" allowfullscreen)

</template>

<script>
  export default {
    name: "youtube",
    props: {
      link: '',
	    image: ''
    }
  }
</script>

<style lang="scss">
	.youtube {
		&__item {
			position: relative;
			img {
				width: 100%;
				height: 100%;
				object-fit: contain;
				object-position: center;
				font-family: 'object-fit: contain; object-position: bottom;';
			}
		}
	}

</style>