var gulp = require('gulp');
var inlineCss = require('gulp-inline-css');
var elixir = require('laravel-elixir');
var rename = require('gulp-rename');

require('laravel-elixir-vueify');
require('laravel-elixir-eslint');

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

gulp.task('emailify', function() {
  return gulp.src('resources/templates/emails/*.html')
    .pipe(inlineCss())
    .pipe(rename(function(path){
      path.extname = ".blade.php";
      return path;
    }))
    .pipe(gulp.dest('resources/views/emails/'));
});

elixir(function(mix) {
  mix.sass('app.scss');
  mix.browserify('main.js');

  mix.version(['css/app.css', 'js/main.js'])

  mix.task('emailify');
  mix.eslint('resources/assets/js');
});
