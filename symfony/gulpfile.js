var gulp = require('gulp');
var minify = require('gulp-minify');
var less = require('gulp-less');
var concat = require('gulp-concat');

var paths = {
  scripts: ['./node_modules/chessboard/*.js', './node_modules/chess.js/chess.min.js'],
  styles: ['./node_modules/chessboard/*.less', './app/Resources/less/**/*.less'],
  //images: ['resources/img/*.svg', './chessboard/*.svg']
};

gulp.task('default', ['js', 'less'])

gulp.task('js', function() {
  gulp.src(paths.scripts)
    .pipe(gulp.dest('./web/js'));

});
/*
gulp.task('images', function() {
  gulp.src(paths.images)
    .pipe(gulp.dest('./web/img'));
});
*/
gulp.task('less', function () {
  return gulp.src(paths.styles)
    .pipe(less())
    .pipe(concat('style.css'))
    .pipe(gulp.dest('./web/css'));
});

// Rerun the task when a file changes
gulp.task('watch', function() {
  gulp.watch(paths.scripts, ['js']);
  gulp.watch(paths.styles, ['less']);
//  gulp.watch(paths.images, ['images']);
});
