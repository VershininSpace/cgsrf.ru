const mix = require('laravel-mix'); // laravel-mix

const themeName = 'cgsrf'; // October CMS

const paths = { // 
 src: `themes/${themeName}/src`, 
 dist: `themes/${themeName}/assets`
}

mix.setPublicPath(paths.dist)
.js(`${paths.src}/js/app.js`, `${paths.dist}/js/app.js`)
//.extract()
// .sass(`${paths.src}/sass/sytles.scss`, 'styles/main.css')
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
