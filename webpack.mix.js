let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/material-dashboard.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/scss/material-dashboard.scss', 'public/css')
    .js('resources/assets/js/core/jquery.min.js', 'public/js/core')
    .js('resources/assets/js/core/popper.min.js', 'public/js/core')
    .js('resources/assets/js/core/bootstrap-material-design.min.js', 'public/js/core')
    .js('resources/assets/js/plugins/perfect-scrollbar.jquery.min.js', 'public/js/plugins')
    .js('resources/assets/js/plugins/chartist.min.js', 'public/js/plugins')
    .js('resources/assets/js/plugins/bootstrap-notify.js', 'public/js/plugins')
    .css('resources/assets/css/material-dashboard.css', 'public/css');