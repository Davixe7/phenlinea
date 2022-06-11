const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let publicPath = process.env.MIX_PUBLIC_PATH ? process.env.MIX_PUBLIC_PATH : './public/'
mix.setPublicPath( publicPath )

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
mix.webpackConfig({
  plugins: [
    new VuetifyLoaderPlugin()
  ]
})

mix.js('resources/js/app.js', 'js')
.version()
.sass('resources/sass/app.scss', 'css');
