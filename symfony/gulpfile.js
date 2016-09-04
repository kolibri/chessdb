var gulp = require('gulp');
var minify = require('gulp-minify');
var less = require('gulp-less');
var concat = require('gulp-concat');

var paths = {
  scripts: ['./node_modules/chessboard/chessboard*.js', './node_modules/chess.js/chess.min.js'],
  styles: ['./node_modules/chessboard/*.less', './app/Resources/less/**/*.less'],
  purecss: ['./node_modules/purecss/build/pure-min.css']
  //images: ['resources/img/*.svg', './chessboard/*.svg']
};

gulp.task('default', ['js', 'less', 'purecss'])

gulp.task('js', function() {
  gulp.src(paths.scripts)
    .pipe(gulp.dest('./web/js'));

});

gulp.task('purecss', function() {
  gulp.src(paths.purecss)
    .pipe(gulp.dest('./web/css'));
});

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
  gulp.watch(paths.purecss, ['purecss']);
});
