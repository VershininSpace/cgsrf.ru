module.exports = {
  content: [
    "./themes/cgsrf/pages/**/*.htm",
    "./themes/cgsrf/partials/**/*.htm",
    "./themes/cgsrf/content/**/*.htm",
    "./themes/cgsrf/layouts/**/*.htm",
  ],
  theme: {
    extend: {
			colors: {
      	'primary': {
					'800' : '#11405d',
					'DEFAULT' : '#11405d',
				}
    	},
		},
  },
  plugins: [],
}
