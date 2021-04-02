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
    .copy('resources/frontpage/main.css', 'public/css')
    .copyDirectory('resources/images', 'public/images')
    .copyDirectory('resources/fa', 'public/css/fa')
    // For now don't use purgeCss as it is removing css elements that
    // are used, but is not apparently finding them.
    // .purgeCss()
    .disableNotifications();


mix.postCss('resources/css/tailwind.css', 'public/css/app.css', [
    require('postcss-import'),
    require('tailwindcss'),
]);
