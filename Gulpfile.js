
var src          = [],
    web          = [];

src['style']     = "src/Ressources/styles/**/*.scss";
web['style']     = "web/css/";

var gulp         = require('gulp'),
    sass         = require('gulp-sass'),
    concat       = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    rename       = require('gulp-rename'),
    notify       = require('gulp-notify'),
    minifycss    = require('gulp-minify-css'),
    del          = require('del');

// Task to compile Sass files
gulp.task('styles', function() {
    console.log(process.env);
    gulp.src(src['style'])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(concat({ path: 'app.min.css', stat: { mode: 0666 }}))
        .pipe(gulp.dest(web['style']))
    ;
});

gulp.task('clean', function(cb) {
    del([web['style']], cb); // we use cb callback to ensure the task finished before exiting
});

// Default task to run tasks below by using "gulp" command line
gulp.task('default', ['clean'], function() {
    gulp.start('styles');
});

gulp.task('watch', function() {
    gulp.watch(src['style'], ['styles']);
});