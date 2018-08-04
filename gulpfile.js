var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var del = require('del');
var mergeStream = require('merge-stream');
var streamQueue = require('streamqueue');
var clearRequire = require('clear-require');

gulp.task('sass', function () {
  return gulp.src('./assets/sass/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('/'))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('js-clear', function () {
  del(['./public/js/*.*']);
});

gulp.task('js', ['js-clear'], function () {
  clearRequire('./gulp-bundles.js');
  var bundles = require('./gulp-bundles.js').bundles;

  return mergeStream(
    bundles.map(
      function (bundle) {
        return streamQueue({objectMode: true},
          gulp.src(bundle.dependencies).pipe(sourcemaps.init()),
          gulp.src(bundle.sources).pipe(sourcemaps.init()).pipe(uglify())
        )
          .pipe(concat(bundle.filename))
          .pipe(sourcemaps.write('/'))
          .pipe(gulp.dest('./public/js/' + bundle.destination));
      }
    )
  );
});

gulp.task('img', function () {
  gulp
    .src(['./assets/images/**/*'])
    .pipe(gulp.dest('./public/images'));
});

gulp.task('watch', ['img', 'sass', 'js'], function () {
  gulp.watch([
    './assets/sass/**/*.scss'
  ], ['sass']);
  gulp.watch([
    './gulp-bundles.js',
    './assets/js/*.js',
    './assets/js/**/*.js',
  ], ['js']);
});

gulp.task('default', ['img', 'sass', 'js', 'watch']);
