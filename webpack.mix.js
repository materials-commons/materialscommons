const mix = require('laravel-mix');
require('laravel-mix-purgecss');

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
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/datatables/datatables.min.js', 'public/js')
    .copy('resources/datatables/datatables.min.css', 'public/css')
    .copyDirectory('resources/fa', 'public/css/fa')
    .purgeCss()
    .disableNotifications();
