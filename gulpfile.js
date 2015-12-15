var gulp = require('gulp');
var zip = require('gulp-zip');

gulp.task('zip', function() {
  return gulp.src(['../jr*/**', '!./node_modules/**'])
    .pipe(zip('jr-shop.zip'))
    .pipe(gulp.dest('../'));
})
