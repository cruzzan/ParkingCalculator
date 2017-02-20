var elixir = require('laravel-elixir');
var gulp = require('gulp');
var phpunit = require('gulp-phpunit');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
});

gulp.task('phpunit', function() {
    gulp.src('phpunit.xml').pipe(phpunit());
});
