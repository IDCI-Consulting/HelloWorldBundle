'use strict';

var src          = [],
    web          = []
;

src['style']     = "src/Ressources/styles/**/*.scss";
src['template']  = "templates/**/*";
src['script']    = "src/Ressources/js/**/*.js";
web['style']     = "web/css/";
web['script']    = "web/js/";

var autoprefixer = require('gulp-autoprefixer'),
    chmod        = require('gulp-chmod'),
    concat       = require('gulp-concat'),
    del          = require('del'),
    gulp         = require('gulp'),
    jshint       = require('gulp-jshint'),
    livereload   = require('gulp-livereload'),
    minifycss    = require('gulp-minify-css'),
    notify       = require('gulp-notify'),
    rename       = require('gulp-rename'),
    sass         = require('gulp-sass'),
    uglify       = require('gulp-uglify')
;

// Task to compile Sass files
gulp.task('styles', function() {
    gulp.src(src['style'])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(concat({ path: 'app.min.css', stat: { mode: 0666 }}))
        .pipe(gulp.dest(web['style']))
        .pipe(livereload())
    ;
});

gulp.task('reload-templates', function() {
    gulp.src(src['template'])
        .pipe(livereload())
    ;
});

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(src['style'], ['styles']);
    gulp.watch(src['template'], ['reload-templates']);
});
