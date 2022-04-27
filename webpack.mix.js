const mix = require('laravel-mix'); // laravel-mix
const tailwindcss = require('tailwindcss'); /* Add this line at the top */

const themeName = 'cgsrf'; // October CMS

const paths = { // 
 src: `themes/${themeName}/src`, 
 dist: `themes/${themeName}/assets`
}

mix.setPublicPath(paths.dist)
  .js(`${paths.src}/js/app.js`, `${paths.dist}/js/app.js`)
    // .sass(`${paths.src}/sass/styles.scss`, `${paths.dist}/css/styles.css`)
    // .options({
    //     postCss: [ tailwindcss('./tailwind.config.js') ],
    // })
    .postCss(`${paths.src}/css/styles.css`, `${paths.dist}/css/styles.css`, [
      require("tailwindcss"),
    ])
.version()
.options({
    processCssUrls: false
})
// .copyDirectory(`${paths.src}/fonts`, `${paths.dist}/fonts`)
// .copyDirectory(`${paths.src}/images`, `${paths.dist}/images`)
.browserSync({
    watch: true,
    proxy: {
        target: "http://gazprom.loc/"
    }
});
