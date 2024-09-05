const mix = require('laravel-mix');

if( process.env.APP_ENV == 'production' ){
    mix.options({
        // Can't use 443 here because address already in use
        hmrOptions: { host: 'phenlinea.com', port: 8080 }
    });
}
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

if( process.env.APP_ENV == 'local'){
    mix
    .js('resources/js/app.js', 'js')
    .js('resources/js/super.js', 'js')
    .vue()
    .sass('resources/sass/app.scss', 'css');
}else {
    mix
    .js('resources/js/app.js', 'js')
    .js('resources/js/super.js', 'js')
    .vue()
    .version()
    .sass('resources/sass/app.scss', 'css');
}