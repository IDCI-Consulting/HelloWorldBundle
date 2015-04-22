'use strict';

var src             = [],
    web             = [],
    bower           = []
;

src['style']        = "src/Ressources/styles/**/*.scss";
src['template']     = "templates/**/*";
src['script']       = "src/Ressources/js/**/*.js";
src['manifest']     = "src/Ressources/manifest/";
web['style']        = "web/css/";
web['script']       = "web/js/";
bower['foundation'] = "bower_components/foundation/scss/*.scss";

var chmod           = require('gulp-chmod'),
    concat          = require('gulp-concat'),
    del             = require('del'),
    gulp            = require('gulp'),
    jshint          = require('gulp-jshint'),
    livereload      = require('gulp-livereload'),
    minifycss       = require('gulp-minify-css'),
    rename          = require('gulp-rename'),
    rev             = require('gulp-rev'),
    sass            = require('gulp-sass'),
    uglify          = require('gulp-uglify')
;

// Task to compile Sass files
gulp.task('styles', function() {
    // delete all css files
    del(web['style']+'/*.css');
    // build css files
    gulp.src([src['style'], bower['foundation']])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(concat({ path: 'app.min.css'}))
        .pipe(rev())
        .pipe(chmod(755))
        .pipe(gulp.dest(web['style']))
        .pipe(rev.manifest())
        .pipe(gulp.dest(src['manifest']))
        .pipe(livereload())
    ;
});

// Task to put the JS files in web folder
gulp.task('scripts', function() {
    return gulp.src(src['script'])
        .pipe(chmod(755))
        .pipe(gulp.dest(web['script']))
        .pipe(livereload())
    ;
});

// Task to minify Js
gulp.task('minify-js', function() {
    return gulp.src(src['script'])
        .pipe(chmod(755))
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(uglify())
        .pipe(concat({ path: 'app.min.js'}))
        .pipe(gulp.dest(web['script']))
    ;
});

// Task to clean folder content
gulp.task('clean', function(callback) {
    del([web['script'], web['style']], callback); //we use callback to ensure the task finishes before exiting
});

// Task to run before prod deployment
gulp.task('prod', ['clean'], function() {
    gulp.start('styles', 'minify-js');
});

gulp.task('reload-templates', function() {
    gulp.src(src['template'])
        .pipe(livereload())
    ;
});

gulp.task('watch', ['styles'], function() {
    livereload.listen();
    gulp.watch(src['style'], ['styles']);
    gulp.watch(src['template'], ['reload-templates']);
    gulp.watch(src['script'], ['scripts']);
});
