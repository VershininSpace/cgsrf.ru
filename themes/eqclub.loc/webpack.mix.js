let mix = require('laravel-mix');

const themeName = 'ei'; 
const paths = { 
  src: `themes/${themeName}/src`, 
  dist: `themes/${themeName}/assets`
}

mix.setPublicPath(paths.dist)
.js(`${paths.src}/js/app.js`, `${paths.dist}/js`)
.vue({
  extractStyles: true,
  globalStyles: false
})
//  .sass(`${paths.src}/scss/style.scss`, `${paths.dist}/css`)
.autoload({ jquery: ['$', 'jQuery', 'window.jQuery']})
// .extract([`./modules/system/assets/js/framework.js`])
.sourceMaps()
.disableNotifications()
.browserSync({
  watch: true,
  proxy: {
      target: "http://eqclub.loc/"
  }
});