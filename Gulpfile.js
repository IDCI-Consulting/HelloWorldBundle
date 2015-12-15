'use strict';

var src              = [],
    web              = [],
    foundation       = {
        script: {
            jquery: "bower_components/foundation/js/vendor/jquery.js",
            foundation: "bower_components/foundation/js/foundation.min.js"
        }
    },
    slick = 'bower_components/slick-carousel/slick/'
;

// set the folder name and the relative paths
// in the example the images are in ./assets/images
// and the public directory is ../public
var imagePaths = {
    src: 'web/images/',
    dest: 'web/images'
};

// create an array of image groups (see comments above)
// specifying the folder name, the ouput dimensions and
// whether or not to crop the images
var images = [
    { folder: 'resize', width: 800, height: 500, crop: true }
];

src['style']         = ["src/Resources/styles/scss/main.scss", 'bower_components/slick-carousel/slick/slick.scss'];
src['template']      = "templates/**/*";
src['script']        = "src/Resources/js/**/*.js";
src['plugins']       = "src/Resources/plugins/**/*";
src['manifest']      = "src/Resources/manifest/";
src['images']        = "web/images/spritify/*.png";
web['style']         = "web/css/";
web['script']        = "web/js/";
web['images']        = "web/images/spritify/";
web['plugins']       = "web/plugins/";

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
    uglify           = require('gulp-uglify'),
    spritesmith      = require('gulp.spritesmith'),
    imageresize      = require('gulp-image-resize'),
    imagemin         = require('gulp-imagemin'),
    pngquant         = require('imagemin-pngquant')
;

// Task to watch files
gulp.task('watch', ['init'], function() {
    livereload.listen();
    gulp.watch("src/Resources/styles/**/*.scss", ['styles']);
    gulp.watch(src['template'], ['reload-templates']);
    gulp.watch(src['script'], ['dev-scripts']);
});

// Task to launch before watch
gulp.task('init', ['styles', 'dev-scripts'], function() {
    gulp.src([foundation.script.jquery, foundation.script.foundation])
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

    gulp.start('cv-theme');

    gulp.src(src['style'])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(concat({ path: 'app.min.css'}))
        .pipe(rev())
        .pipe(chmod(775))
        .pipe(gulp.dest(web['style']))
        .pipe(rev.manifest(src['manifest']+'rev-manifest.json', {base: src['manifest'], merge: true}))
        .pipe(gulp.dest(src['manifest']))
        .pipe(livereload())
    ;
});

gulp.task('cv-theme', function() {
    // build css files
    gulp.src(['src/Resources/styles/scss/theme/*.scss'])
        .pipe(sass({ errLogToConsole: true }))
        .pipe(minifycss({keepSpecialComments: 0}))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(chmod(775))
        .pipe(gulp.dest('web/css/cv/theme/'))
        .pipe(livereload())
    ;
});

// Task to install scripts in a dev environment
gulp.task('dev-scripts', function(cb) {
    // move js files
    gulp.src([src['script'], slick+'slick.js'])
        /*.pipe(jshint())
        .pipe(jshint.reporter('default'))*/
        .pipe(gulp.dest(web['script']))
        .pipe(chmod(775))
        .pipe(livereload())
    ;

    // move ace editor files
    return gulp.src(src['plugins'])
        .pipe(gulp.dest(web['plugins']))
        .pipe(chmod(775))
    ;
});

// Task to install scripts in a prod environment
gulp.task('prod-scripts', function(cb) {
    // delete all css files
    del(web['script']+'/*.js');
    gulp.src([foundation.script.foundation, src['script'], slick+'slick.js'])
        .pipe(uglify())
        .pipe(concat({ path: 'app.min.js'}))
        .pipe(rev())
        .pipe(chmod(775))
        .pipe(gulp.dest(web['script']))
        .pipe(rev.manifest(src['manifest']+'rev-manifest.json', {base: src['manifest'], merge: true}))
        .pipe(gulp.dest(src['manifest']))
    ;

    //Hack: we need to uglify jquery separately because we want to load its in the head tag
    gulp.src([foundation.script.jquery])
        .pipe(uglify())
        .pipe(concat({ path: 'jquery.min.js'}))
        .pipe(chmod(775))
        .pipe(gulp.dest(web['script']))
    ;

    // move ace editor files
    return gulp.src(src['plugins'])
        .pipe(gulp.dest(web['plugins']))
        .pipe(chmod(775))
    ;
});

// Task to clean folder content
gulp.task('clean', function(callback) {
    del([web['script'], web['style']], callback); //we use callback to ensure the task finishes before exiting
});

// Task to run before prod deployment
gulp.task('prod', ['clean'], function() {
    gulp.start('styles', 'prod-scripts');
});

// Live-reload after editing templates file
gulp.task('reload-templates', function() {
    gulp.src(src['template'])
        .pipe(livereload())
    ;
});

/*****************
 * Utility tasks *
 *****************/

// Task to create a sprite
gulp.task('sprite', function () {
    gulp.src(src['images'])
        .pipe(spritesmith({
            imgName: 'sprite.png',
            cssName: 'sprite.css'
        }))
        .pipe(gulp.dest(web['images']))
    ;
});

// images gulp task
gulp.task('resize-images', function () {

    // loop through image groups
    images.forEach(function(type){

        // build the resize object
        var resize_settings = {
            width: type.width,
            crop: type.crop,
            // never increase image dimensions
            upscale : false
        };

        // only specify the height if it exists
        if (type.hasOwnProperty("height")) {
            resize_settings.height = type.height
        }

        gulp
            // grab all images from the folder
            .src(imagePaths.src+type.folder+'/**/*')
            // resize them according to the width/height settings
            .pipe(imageresize(resize_settings))
            // optimize the images
            .pipe(imagemin({
                progressive: true,
                // set this if you are using svg images
                svgoPlugins: [{removeViewBox: false}],
                use: [pngquant()]
            }))
            .pipe(chmod(775))
            // output each image to the dest path
            // maintaining the folder structure
            .pipe(gulp.dest(imagePaths.dest));
    });
});
