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

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/home.js', 'public/js')
   .js('resources/js/navbar.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/welcome.scss', 'public/css')
   .sass('resources/sass/navbar.scss', 'public/css')
   .sass('resources/sass/home.scss', 'public/css')
   .sass('resources/sass/room.scss', 'public/css')
   .sass('resources/sass/room_setting.scss', 'public/css');

