var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function () {
  return gulp.src('./assets/sass/app.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('/'))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('img', function() {
  gulp.src([
    'assets/images/**/*'
  ])
    .pipe(gulp.dest('public/images'));
});

gulp.task('watch', ['img', 'sass'], function () {
  gulp.watch([
    './assets/sass/**/*.scss'
  ], ['sass']);
});

gulp.task('default', ['img', 'sass', 'watch']);
