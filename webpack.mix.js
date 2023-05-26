const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Backpack maintainers use mix to:
 | - install and update CSS and JS assets;
 | - copy everything that needs to be published into src/public
 |
 | All JS will be bundled into one file (see bundle.js).
 |
 | How to use (for maintainers only):
 | - cd vendor/backpack/crud
 | - npm install
 | - npm run prod
 | (this will also publish the assets for you to test, so no need to do that too)
 */

// merge all needed JS into a big bundle file
mix.sourceMaps(false, 'source-map').js('src/resources/js', 'dist/assets/editor.js')
    .sass('src/resources/scss/gjs.scss','dist/assets/editor.css')
    .options({
        processCssUrls: false
    });

mix.copyDirectory('node_modules/grapesjs/dist/fonts', 'dist/fonts')
mix.copyDirectory('src/resources/js/plugins/image-editor/svg', 'dist/svg')


if(!mix.inProduction()){
    // mix.copyDirectory('dist', '../../../public/vendor/laravel-grapesjs')
}