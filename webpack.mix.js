const mix = require('laravel-mix');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/site/js/app.js', 'public/assets/js')
    .js('resources/admin/js/app.js', 'public/assets/admin/js')
    .vue()
    .sass('resources/site/scss/app.scss', 'public/assets/css')
    .sass('resources/admin/scss/admin.scss', 'public/assets/admin/css')
    .options({
        processCssUrls: false,
        processJsUrls:false
    })
    .webpackConfig({
        plugins: [
            new SVGSpritemapPlugin('resources/site/svg/*.svg',{
                output:{
                    filename:'assets/site/images/sprites.svg'
                }
            }),
        ],
        resolve: {
            extensions: ['.js', '.vue', '.json', '.styl','.scss','.css'],
            alias: {
                '@': __dirname + '/resources/site/js',
            },
        },
    })
;
