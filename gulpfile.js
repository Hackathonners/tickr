var path = require('path');
var gulp = require('gulp');
var inlineCss = require('gulp-inline-css');
var elixir = require('laravel-elixir');
var rename = require('gulp-rename');

require('laravel-elixir-vue-2');
require('laravel-elixir-webpack-official');

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
Elixir.webpack.mergeConfig({
  resolve: {
    extensions: ['', '.js', '.vue'],
    alias: {
      'app': path.resolve(Elixir.config.assetsPath, 'js/app/'),
    }
  },
  module: {
    loaders: [{
      test: /\.json$/,
      loader: 'json'
    }]
  }
});

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
  mix.sass('app.scss')
     .webpack('main.js');

  mix.version(['css/app.css', 'js/main.js'])

  mix.task('emailify');
  // mix.eslint('resources/assets/js');
});
