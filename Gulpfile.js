'use strict';

var src              = [],
    web              = [],
    foundation       = []
;

src['style']         = "src/Ressources/styles/**/*.scss";
src['template']      = "templates/**/*";
src['script']        = "src/Ressources/js/**/*.js";
src['manifest']      = "src/Ressources/manifest/";
web['style']         = "web/css/";
web['script']        = "web/js/";
foundation['styles'] = "bower_components/foundation/scss/*.scss";
foundation['script'] = [
    "bower_components/foundation/js/vendor/modernizr.js",
    "bower_components/foundation/js/vendor/jquery.js",
    "bower_components/foundation/js/foundation.min.js"
];

var chmod            = require('gulp-chmod'),
    concat           = require('gulp-concat'),
    del              = require('del'),
    gulp             = require('gulp'),
    jshint           = require('gulp-jshint'),
    livereload       = require('gulp-livereload'),
    minifycss        = require('gulp-minify-css'),
    rename           = require('gulp-rename'),
    rev              = require('gulp-rev'),
    sass             = require('gulp-sass'),
    uglify           = require('gulp-uglify')
;

// Task to watch files
gulp.task('watch', ['init'], function() {
    livereload.listen();
    gulp.watch(src['style'], ['styles']);
    gulp.watch(src['template'], ['reload-templates']);
    gulp.watch(src['script'], ['dev-scripts']);
});

// Task to launch before watch
gulp.task('init', ['styles', 'dev-scripts'], function() {
    gulp.src(foundation['script'])
        .pipe(gulp.dest(web['script']))
        .pipe(chmod(775))
        .pipe(livereload())
    ;
});

// Task to compile Sass files
gulp.task('styles', function() {
    // delete all css files
    del(web['style']+'/*.css');
    // build css files
    gulp.src([src['style'], foundation['styles']])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(concat({ path: 'app.min.css'}))
        .pipe(rev())
        .pipe(chmod(775))
        .pipe(gulp.dest(web['style']))
        .pipe(rev.manifest())
        .pipe(gulp.dest(src['manifest']))
        .pipe(livereload())
    ;
});

// Task to install scripts in a dev environment
gulp.task('dev-scripts', function() {
    // move js files
    gulp.src(src['script'])
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(gulp.dest(web['script']))
        .pipe(chmod(775))
        .pipe(livereload())
    ;
});

// TODO TO_BE_REMOVED (PROD BRANCH)
// Task to install scripts in a prod environment
gulp.task('prod-scripts', function() {
    gulp.src(src['script'])
        .pipe(uglify())
        .pipe(concat({ path: 'app.min.js'}))
        .pipe(rev())
        .pipe(chmod(775))
        .pipe(gulp.dest(web['script']))
        .pipe(rev.manifest())
        .pipe(gulp.dest(src['manifest']))
    ;
});

// Task to clean folder content
gulp.task('clean', function(callback) {
    del([web['script'], web['style']], callback); //we use callback to ensure the task finishes before exiting
});

// Task to run before prod deployment
// TODO / TO_CHECK
gulp.task('prod', ['clean'], function() {
    gulp.start('styles', 'prod-scripts');
});

// Live-reload after editing templates file
gulp.task('reload-templates', function() {
    gulp.src(src['template'])
        .pipe(livereload())
    ;
});
