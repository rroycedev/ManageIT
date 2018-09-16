'use strict';

var gulp = require('gulp');
/*
gulp.task('default',  function () {
        console.log("Default function called");
});
*/
var elixir = require('laravel-elixir');

// gulp.task( 'default',  [ 'elixir' ] );

elixir(function (mix) {
        mix.copy('resources/assets/img/sidebar-4.jpg', 'public/images');
        mix.copy('resources/assets/img/sidebar-3.jpg', 'public/images');
        mix.copy('resources/assets/img/sidebar-2.jpg', 'public/images');
        mix.copy('resources/assets/img/sidebar-1.jpg', 'public/images');
        mix.copy('resources/assets/img/new_logo.png', 'public/images');
        mix.copy('resources/assets/img/mask.png', 'public/images');
        mix.copy('resources/assets/img/favicon.png', 'public/images');
        mix.copy('resources/assets/img/cover.jpg', 'public/images');
        mix.copy('resources/assets/img/apple-icon.png', 'public/images');
        mix.copy('resources/assets/css/material-dashboard.css', 'public/css');
        mix.copy('resources/assets/css/sidebar-menu.css', 'public/css');
    	mix.copy('resources/assets/css/manageit-dashboard.css', 'public/css');

});


