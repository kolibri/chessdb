var gulp = require('gulp');
var less = require('gulp-less');
var concat = require('gulp-concat');

var paths = {
    scripts: [
        './node_modules/chessboard/chessboard*.js',
        './node_modules/chess.js/chess*.js'
    ],
    less: [
        './app/Resources/less/**/*.less'
    ],
    css: [
        './node_modules/chessboard/**/*.css',
        './node_modules/milligram/dist/**/*.css'
    ]
}

gulp.task('default', ['build', 'watch'])

gulp.task('build', ['js', 'less', 'css'])

gulp.task('js', function () {
    gulp.src(paths.scripts)
        .pipe(gulp.dest('./web/js'))

})

gulp.task('less', function () {
    return gulp.src(paths.less)
        .pipe(less())
        .pipe(concat('style.css'))
        .pipe(gulp.dest('./web/css'))
})

gulp.task('css', ['less'], function () {
    gulp.src(paths.css)
        .pipe(gulp.dest('./web/css'))
})

// Rerun the task when a file changes
gulp.task('watch', function () {
    gulp.watch(paths.scripts, ['js'])
    gulp.watch(paths.less, ['less'])
    gulp.watch(paths.css, ['css'])
})
